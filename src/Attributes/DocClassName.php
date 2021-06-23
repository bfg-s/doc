<?phpnamespace Bfg\Doc\Attributes;use Attribute;/** * Class DocClassName * @package Bfg\Doc\Attributes */#[Attribute(Attribute::TARGET_PROPERTY)]class DocClassName{    /**     * @var string|null     */    public ?string $namespace = null;    /**     * DocClassName constructor.     * @param  string  $name     */    public function __construct(        public string $name    ) {        $exploded = explode("\\", $this->name);        $last_key = array_key_last($exploded);        $this->name = $exploded[$last_key];        unset($exploded[$last_key]);        $exploded = array_values($exploded);        if ($exploded) {            $this->namespace = implode("\\", $exploded);        }    }}