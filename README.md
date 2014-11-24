Simple Multi Custom Post Types
===========

#### Update!
Now allows for an array of multiple taxonomies to be passed to each cpt. Also the ability to filter/sort them, as well as post type term count. Also added $hide[] to hide taxs from backends table row header and filter/sort dropdowns.

### What?
A plugin that can handle multiple custom post types at once, requires atleast the singular and plural name of each cpt you would like to add, and optionally a custom taxonomy and slug rewrite.

Following the philosophy of keeping custom post types theme agnostic, this plugin follows a simplified version of Simple CPT Plugin. Although possible to input your values within the plugin code, it is **not** recommended, and to rather hook into it with the below code. This chunk is small enough to be easily placed within any theme - and although contradictory, its preferred over having multiple cpt plugins cluttering your project.

### Why?
Came across a project requiring a ridiculous amount of custom post types, and didn't think that having over 10 separate plugins for handling those post types were ideal - so I made one to rule them all. ba-dum-dum-tshh.

### How?
1. Download/Clone, install and activate plugin.
2. Place hook into theme functions. ( Preferably into abstracted module, look @ simple. ) Note: Capitalise first letter.
3. Notice how not every post type needs a tax or rewrite, however since this plugin works in array association, you will need to add an empty string for that relation. Allows for an array of multiple taxonomies to be passed to each cpt.

```
// hook into simple multi cpts
add_filter( 'simple_multi_cpts_plugin_init', 'simple_child_cpts' );
function simple_child_cpts() {

    global $child_cpts;

    // Required
    $cpt_name = array(
        'Agency',
        'Client',
        'Project'
    );

    // Required
    $cpt_plural = array(
        'Agencies',
        'Clients',
        'Projects'
    );

    // Optional
    $cpt_tax = array(
        'Locations',
        '',
        array('Type', 'Url', 'Skills')
    );

    // Optional
    $rewriteUrl = array(
        'Agencies',
        '',
        'Work'
    );

    // Optional - set to true to hide from backend
    $hide = array(
        '',
        true,
        array(
            '',
            true,
            ''
        )
    );

    $child_cpts = array($cpt_name, $cpt_plural, $cpt_tax, $rewriteUrl, $hide);

    return $child_cpts;

}
```

### Look at
https://gist.github.com/kiriaze/1ba01fd6f4287766922f    
https://gist.github.com/kiriaze/f4c4664889a21731fecf

### To Do's
1. Optimize Code
	* Some code is inconsistent, flush out.
    * Nested forloops not ideal, look at index based comparison of arrays.
2. ACF Settings
	* To create cpts from admin if acf is active, rather than through functions.php
3. Integrate new structure into other simple plugins

### Beer?
[Beer me if ya want ;)](https://plasso.co/ckiriaze@gmail.com)
