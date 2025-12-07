<?php

// Enqueue styles
function stanicky_enqueue_styles() {
    wp_enqueue_style( 'stanicky-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'stanicky_enqueue_styles' );

// Set up theme supports and other features
function stanicky_setup() {
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style.css' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'custom-line-height' );
    add_theme_support( 'custom-spacing' );
    add_theme_support( 'custom-units' );
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'stanicky' ),
    ) );
}
add_action( 'after_setup_theme', 'stanicky_setup' );

function stanicky_enqueue_scripts() {
    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue custom script with jQuery as a dependency
    wp_enqueue_script( 'combine-menus', get_template_directory_uri() . '/assets/js/combine-menus.js', array('jquery'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'stanicky_enqueue_scripts' );

// Load theme textdomain for translations
function stanicky_textdomain() {
    load_theme_textdomain( 'stanicky', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'stanicky_textdomain' );

// Automatic theme updates from the GitHub repository
add_filter( 'pre_set_site_transient_update_themes', 'automatic_GitHub_updates', 100, 1 );
function automatic_GitHub_updates( $data ) {
    // Theme information
    $theme   = get_stylesheet(); // Folder name of the current theme
    $current = wp_get_theme()->get( 'Version' ); // Get the version of the current theme
    // GitHub information
    $user = 'valamos'; // The GitHub username hosting the repository
    $repo = 'stanicky'; // Repository name as it appears in the URL

    $release = get_transient( 'stanicky_latest_release' );

    if ( false === $release ) {
        $response = wp_remote_get(
            'https://api.github.com/repos/' . $user . '/' . $repo . '/releases/latest',
            array(
                'headers' => array(
                    'User-Agent' => $user,
                    'Accept'     => 'application/vnd.github+json',
                ),
                'timeout' => 10,
            )
        );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return $data;
        }

        $release = json_decode( wp_remote_retrieve_body( $response ) );

        if ( null === $release ) {
            return $data;
        }

        set_transient( 'stanicky_latest_release', $release, DAY_IN_SECONDS );
    }

    if ( ! $release ) {
        return $data;
    }

    $update = filter_var( $release->tag_name ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

    // Only return a response if the new version number is higher than the current version
    if ( $update && version_compare( $update, $current, '>' ) ) {
        $package_url = $release->assets[0]->browser_download_url ?? '';

        if ( ! $package_url ) {
            return $data;
        }

        $data->response[ $theme ] = array(
            'theme'       => $theme,
            // Strip the version number of any non-alpha characters (excluding the period)
            // This way you can still use tags like v1.1 or ver1.1 if desired
            'new_version' => $update,
            'url'         => 'https://github.com/' . $user . '/' . $repo,
            'package'     => $package_url,
        );
    }

    return $data;
}

?>
