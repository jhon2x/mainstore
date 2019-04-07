<?php

namespace Acidgreen\ContentManager\Model\Content;

abstract class AbstractContent {
    abstract function upsert(string $identifier, Array $data);
}