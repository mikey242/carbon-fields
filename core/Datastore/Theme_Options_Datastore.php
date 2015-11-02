<?php 

namespace Carbon_Fields\Datastore;

use Carbon_Fields\Field\Base_Field;

class Theme_Options_Datastore extends Base_Datastore {
	function init() {}

	function save(Base_Field $field) {
		$name = $field->get_name();
		$autoload = $field->get_autoload() ? 'yes': 'no';

		// Add value to the cache, so that add_option always works
		$notoptions = wp_cache_get( 'notoptions', 'options' );
		$notoptions[$name] = '';
		wp_cache_set( 'notoptions', $notoptions, 'options' );

		if ( !add_option($name, $field->get_value(), null, $autoload) ) {
			update_option($name, $field->get_value());
		}
	}

	function load(Base_Field $field) {
		$field->set_value( get_option($field->get_name()) );
	}

	function delete(Base_Field $field) {
		delete_option($field->get_name());
	}

	function load_values($field) {
		global $wpdb;

		if ( is_object($field) && is_subclass_of($field, 'Carbon_Fields\\Field\\Base_Field') ) {
			$meta_key = $field->get_name();
		} else {
			$meta_key = $field;
		}

		return $wpdb->get_results('
			SELECT option_name AS field_key, option_value AS field_value FROM ' . $wpdb->options . '
			WHERE `option_name` LIKE "' . addslashes($meta_key) . '_%"
		', ARRAY_A);
	}

	function delete_values(Base_Field $field) {
		global $wpdb;

		$group_names = $field->get_group_names();
		$field_name = $field->get_name();

		$option_name_constraint = '`option_name` LIKE "' . $field_name . implode('-%" OR `option_name` LIKE "' . $field_name, $group_names) . '-%"';

		return $wpdb->query('
			DELETE FROM ' . $wpdb->options . '
			WHERE (' . $option_name_constraint . ')
		');
	}
}