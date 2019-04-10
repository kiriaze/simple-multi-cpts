Simple Multi Custom Post Types
===========

#### Updates Galore!
* Multiple taxonomies can now be passed to each cpt, supports arrays. 
* Ability to filter/sort them, as well as post type term count.
* Hide taxonomies backend table row header and filter/sort dropdowns through $hide[];
* Custom post type icons from FontAwesome!
* ACF Settings integration, now managable from wp admin instead of through functions file, or both if desired! Woot woot!

### What?
A plugin that can handle multiple custom post types at once, requires atleast the singular and plural name of each cpt you would like to add, and optionally a custom taxonomy, slug rewrite, hide options, and icon.

Following the philosophy of keeping custom post types theme agnostic, this plugin is lightweight and efficient and allows for adding infinite custom post types/taxonomies through wp admin ( Requires ACF ) or by hooking into the function within your theme, look at example below. This chunk is small enough to be easily placed within any theme - and is preferred over having multiple cpt plugins cluttering and slowing down your project.

### Why?
Came across a project requiring a ridiculous amount of custom post types, and didn't think that having over 10 separate plugins for handling those post types were ideal - so I made one to rule them all. ba-dum-dum-tshh.

### How?
1. Download/Clone/Composer that ish, install and activate plugin.
2. ACF active? ( And it should be guys! )
	* Skip the rest and head to the plugin settings page, else continue.
3. If not, place hook into theme functions. ( Preferably into abstracted module, look @ simple. ) Note: Capitalise first letter.
4. Notice how not every post type needs a tax or rewrite, however since this plugin works in array association, you will need to add an empty string for that relation. Allows for an array of multiple taxonomies to be passed to each cpt.

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
    
    // Optional -  post type icons, e.g. unicode stripped to \f037
    $cpt_icon = array(
        '\f037',
    );

    $child_cpts = array($cpt_name, $cpt_plural, $cpt_tax, $rewriteUrl, $hide, $cpt_icon);

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
2. Integrate new structure/philosophy into other simple plugins


```
// note: look into passing array differently
// like so:
// [camper] => Array
//     (
//         [cpt_plural] => Campers
//         [rewriteUrl] => Hikers
//         [cpt_tax] => Array
//             (
//                 [0] => Level
//             )

//         [hide] => Array
//             (
//                 [0] =>
//             )

//         [cpt_icon] => \f0fc
//     )

```
