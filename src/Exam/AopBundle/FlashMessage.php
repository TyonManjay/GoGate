<?php

namespace Exam\AopBundle;

/**
 * @Annotation
 */
final class FlashMessage {

    public $key = 'notice';

    public $success = array();

    public $fail = array();

}