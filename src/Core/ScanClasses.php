<?phpnamespace Bfg\Doc\Core;use Bfg\Doc\Attributes\Doc;use Bfg\Doc\Attributes\DocClassName;use Bfg\Route\Attributes\RouteAttribute;use Illuminate\Filesystem\Filesystem;use Illuminate\Support\Collection;use Symfony\Component\Finder\SplFileInfo;/** * Class ScanClasses * @package Bfg\Doc\Core */class ScanClasses{    /**     * @var Collection     */    public Collection $classes;    /**     * ScanClasses constructor.     * @param  Filesystem  $filesystem     * @param  ScanFiles  $scanFiles     */    public function __construct(        protected Filesystem $filesystem,        ScanFiles $scanFiles    ) {        $this->classes = $this->makeList($scanFiles->files);    }    /**     * @param  Collection  $files     * @return Collection     */    protected function makeList(Collection $files): Collection    {        $files = $files->map(fn($i) => ['file' => $i, 'class' => class_in_file($i)])            ->filter(fn(array $i) => $i['class'] && class_exists($i['class']))            ->map(function (array $i) {                $i['props'] = [];                $ref = new \ReflectionClass($i['class']);                if ($ref->isAbstract()) {                    return null;                }                $obj = app()->has($i['class']) ? app($i['class']) : $ref->newInstanceWithoutConstructor();                $ref = (new \ReflectionClass($obj));                $i['class_header'] = file_lines_get_contents($ref->getFileName(), $ref->getStartLine()-1);                $i['old_doc'] = $ref->getDocComment();                foreach ($ref->getProperties() as $property) {                    $property->setAccessible(true);                    $i['props'][$property->name]['doc_template'] =                        $this->getAttribute($property, Doc::class);                    if ($i['props'][$property->name]['doc_template']) {                        $i['props'][$property->name]['value'] = $property->getValue($obj);                        $i['props'][$property->name]['value_type'] = gettype($i['props'][$property->name]['value']);                    }                    $i['props'][$property->name]['doc_name'] =                        $this->getAttribute($property, DocClassName::class);                    //$i['props'][$property->name]['ref'] = $property;                }                $i['props'] = collect($i['props'])->filter(fn ($s) => $s['doc_template'])->toArray();                if ($i['props']) {                    //$i['ref'] = $ref;                    return $i;                }                return null;            })->filter();        return $files;    }    /**     * @param  \ReflectionProperty  $property     * @param  string  $attributeClass     * @return object|null     */    protected function getAttribute(\ReflectionProperty $property, string $attributeClass): ?object    {        $attributes = $property->getAttributes($attributeClass, \ReflectionAttribute::IS_INSTANCEOF);        if (!count($attributes)) {            return null;        }        return $attributes[0]->newInstance();    }}