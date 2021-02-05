<?php

return [
    /**
     * Either privacy and policy acceptance is required or not while registration of new user
     */
    "should_required_privacy_policy" => true,

    /**
     * This is best way to customize your email template for reset password request email
     * Just make your own Notification class and update the class here
     * The package will use this class to send notification email
     */
    "reset_request_notification" => Arif\FleetCartApi\Notifications\PasswordResetRequest::class,
    "reset_success_notification" => Arif\FleetCartApi\Notifications\PasswordResetSuccess::class,

    /**
      * This option will be used only if you are not using your own custom notification class
      */
    "use_mailable" => true,

    /**
     * This option will give you ability to modify me/profile's hit response
     * You can pass full namespace of any controller or class by separating method name with @
     * eg: \App\User@extendsMe
     * Note: If your class or controller is expecting any argument then try to make new class without
     * any parameters in constructor method, otherwise it might cause issue.
     */
    "extend_me" => "\Modules\User\Entities\User@extendsMe",

    /**
     * This variable will be used for pagination. the default will be used.
     * if the request won't have any per_page params
     */
    "per_page" => 10,

    /**
     * /contact endpoint will validate by these roles you will define or customize here.
     * In addition you can also customize the validation messages.
     */
    "contact" => [
        "validation_roles" => [
            'subject' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ],
        "validation_messages" => [],
        "markdown_view" => "fleetcart_api::emails.contact_email",
        "mailable" => \Arif\FleetCartApi\Mail\ContactEmail::class,
        "from_email" => "info@gmail.com",
        "to_email" => "hdarif2@gmail.com",
    ],

    /**
     * For now this package support only one driver passport
     */
    "api_driver" => 'passport',

    /**
     * For now this package support only one driver passport
     */
    'exclude_settings' => [
        'store_name'
    ],
];
