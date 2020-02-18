<?php

/**
 * Plugin Name: Censor Plugin
 * Plugin URI: https://asd.com/
 * Description: Censoring/Replasing specific words with selected characters
 * Version: 1.1.0
 * Author: Eugene
 * Author URI: https://asd.com
 * Text Domain: ***
 *
 */



function cenz_title($title, $id = null)
{
    return cenz($title);
}

function cenz_content($content)
{
    return cenz($content);
}


// Replacing a value with a censor
function cenz($text)
{
    $cenzured_words = get_option('cenz');
    $cenzured_array = explode(", ", $cenzured_words);

    foreach ($cenzured_array as $word) {
        $word = trim($word);
        if (stripos($text, $word, " ") !== FALSE) {
            $text = str_ireplace($word, get_option('zip'), $text);
        }
    }

    return $text;
}

// Filtering title and content in a post with censor
add_filter('the_title', 'cenz_title', 10, 2);
add_filter('the_content', 'cenz_content');


// Adding admin menu
add_action('admin_menu', 'plugin_create_menu');

function plugin_create_menu()
{
    //create new top-level menu
    add_menu_page('Plugin Settings', 'Censor', 'administrator', __FILE__, 'plugin_settings');

    //call register settings function
    add_action('admin_init', 'register_plugin_settings');
}


function register_plugin_settings()
{
    //register our settings
    register_setting('plugin-group', 'cenz');
    register_setting('plugin-group', 'zip');
}


// Making inputs for the Censor Admin Menu Settings
function plugin_settings()
{
    ?>
    <div class="wrap">
        <h1>Censor Plugin</h1>
        <form method="post" action="options.php">
            <?php settings_fields('plugin-group'); ?>
            <?php do_settings_sections('plugin-group'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Add Character</th>
                    <td><input type="text" name="cenz" value="<?php echo get_option('cenz'); ?>"/>
                        <p>After every word added for censorship put ", " before the word</p>
                    </td>
                </tr>


                <tr valign="top">
                    <th scope="row">Add Zipper</th>
                    <td><input type="text" name="zip" value="<?php echo get_option('zip'); ?>"/>
                    </td>
                </tr>
            </table>


            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>