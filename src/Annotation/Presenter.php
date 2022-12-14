<?php

namespace CaioMarcatti12\Webserver\Annotation;

use Attribute;
use CaioMarcatti12\Core\Bean\Annotation\AliasFor;
use CaioMarcatti12\Core\Bean\Enum\BeanType;
use CaioMarcatti12\Webserver\Enum\ContentTypeEnum;

#[AliasFor(BeanType::PRESENTER)]
#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Presenter
{
    protected string $presenterClass = '';
    protected ContentTypeEnum $contentTypeEnum;

    public function __construct(string $presenterClass, ContentTypeEnum $contentTypeEnum)
    {
        $this->presenterClass = $presenterClass;
        $this->contentTypeEnum = $contentTypeEnum;
    }

    public function getPresenterClass(): string
    {
        return $this->presenterClass;
    }

    public function getContentTypeEnum(): ContentTypeEnum
    {
        return $this->contentTypeEnum;
    }
}