<?php echo '<?php' . PHP_EOL;
/**
 * @var $Mailable
 * @var $markdown_path
 * @var $mailable_type
 */
?>
@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
