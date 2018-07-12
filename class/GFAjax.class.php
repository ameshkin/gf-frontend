<?php

/**
 * TODO: All ajax and crud operations may be put here
 */

GFForms::include_addon_framework();

add_action('gform_after_save_form', 'gfActions::add_default_fields', 10, 2);


class GFAjax extends GFAddOn
{







}
