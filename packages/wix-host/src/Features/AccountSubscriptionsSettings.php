<?php

namespace WixCloneHost\Features;

class AccountSubscriptionsSettings
{
    public function __construct()
    {
        add_action('', [$this, 'render_edit_domain']);
        add_action('', [$this, 'render_single_login']);
    }
}