<?phpnamespace Bfg\Doc\Attributes;use Attribute;#[Attribute(Attribute::TARGET_PROPERTY)]class DocMethodsFromArray extends DocMethods{    public function __construct(        public array|string|null $var_type = null,        public ?string $description = null,    ) {        parent::__construct($var_type ?: '{value}', '{key}()', $this->description);    }}