<?php

/**
 * Plugin Name: Censorship Plugin
 * Plugin URI: https://asd.com/
 * Description: Censoring/Repalasing specific words with selected characters
 * Version: 1.1.0
 * Author: Eugene
 * Author URI: https://asd.com
 * Text Domain: ***
 *
 */



function censor_title($title, $id = null)
{
    return censor($title);
}

function censor_content($content)
{
    return censor($content);
}


// Replacing a value with a censor
function censor($text)
{
    $censor_words = get_option('character');
    $censor_array = explode(", ", $censor_words);

    foreach ($censor_array as $word) {
        $word = trim($word);
        if (stripos($text, $word, " ") !== FALSE) {
            $text = str_ireplace($word, get_option('censor'), $text);
        }
    }

    return $text;
}

// Filtering title and content in a post with censor
add_filter('the_title', 'censor_title', 10, 2);
add_filter('the_content', 'censor_content');


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
    register_setting('plugin-group', 'character');
    register_setting('plugin-group', 'censor');
}


// Making inputs for the Censor Admin Menu Settings
function plugin_settings()
{
    ?>
    <div class="wrap">
        <h1>Censorship Plugin</h1>
        <form method="post" action="options.php">
            <?php settings_fields('plugin-group'); ?>
            <?php do_settings_sections('plugin-group'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Add Character</th>
                    <td><input type="text" name="character" value="<?php echo get_option('character'); ?>"/>
                        <p>After every word added for censorship put ", " before the word</p>
                    </td>
                </tr>


                <tr valign="top">
                    <th scope="row">Add Censorship</th>
                    <td><input type="text" name="censor" value="<?php echo get_option('censor'); ?>"/>
                    </td>
                </tr>
            </table>


            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>