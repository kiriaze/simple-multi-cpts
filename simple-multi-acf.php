<?php

if ( function_exists('register_field_group') ) :

register_field_group(
	[
		'key'    => 'group_546f74f3b09b9',
		'title'  => 'Custom Post Types',
		'fields' => [
			[
				'key'               => 'field_546f781c48a45',
				'label'             => 'Custom Post Type',
				'name'              => 'custom_post_type',
				'prefix'            => '',
				'type'              => 'repeater',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'min'          => '',
				'max'          => '',
				'layout'       => 'row',
				'button_label' => 'Add a CPT',
				'sub_fields'   => [
					[
						'key'               => 'field_546f782548a46',
						'label'             => 'Custom Post Type Name',
						'name'              => 'cpt_name',
						'prefix'            => '',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'maxlength'     => '',
						'readonly'      => 0,
						'disabled'      => 0,
					],
					[
						'key'               => 'field_547383cfc0cbb',
						'label'             => 'Custom Post Type Name Plural',
						'name'              => 'cpt_name_plural',
						'prefix'            => '',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'maxlength'     => '',
						'readonly'      => 0,
						'disabled'      => 0,
					],
					[
						'key'               => 'field_546f783b48a47',
						'label'             => 'Custom Taxonomy',
						'name'              => 'cpt_tax',
						'prefix'            => '',
						'type'              => 'repeater',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'min'          => '',
						'max'          => '',
						'layout'       => 'table',
						'button_label' => 'Add A Custom Taxonomy',
						'sub_fields' => [
							[
								'key'               => 'field_54737c37ad4c7',
								'label'             => 'Taxonomy Name',
								'name'              => 'tax_name',
								'prefix'            => '',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => [
									'width' => '',
									'class' => '',
									'id'    => '',
								],
								'default_value' => '',
								'placeholder'   => '',
								'prepend'       => '',
								'append'        => '',
								'maxlength'     => '',
								'readonly'      => 0,
								'disabled'      => 0,
							],
							[
								'key'               => 'field_54737c37ad4c8',
								'label'             => 'Taxonomy Name Plural',
								'name'              => 'tax_name_plural',
								'prefix'            => '',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => [
									'width' => '',
									'class' => '',
									'id'    => '',
								],
								'default_value' => '',
								'placeholder'   => '',
								'prepend'       => '',
								'append'        => '',
								'maxlength'     => '',
								'readonly'      => 0,
								'disabled'      => 0,
							],
							[
								'key'               => 'field_54741b25baf58',
								'label'             => 'Hide Taxonomy',
								'name'              => 'hide_tax',
								'prefix'            => '',
								'type'              => 'true_false',
								'instructions'      => 'Hide from filters and columns.',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => [
									'width' => '',
									'class' => '',
									'id'    => '',
								],
								'message'       => '',
								'default_value' => 0,
							],
						],
					],
					[
						'key'               => 'field_54adb5790c651',
						'label'             => 'Advanced Settings',
						'name'              => 'advanced_settings',
						'prefix'            => '',
						'type'              => 'true_false',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'message'       => '',
						'default_value' => 0,
					],
					[
						'key'               => 'field_5473843a25fae',
						'label'             => 'Rewrite URL',
						'name'              => 'rewrite_url',
						'prefix'            => '',
						'type'              => 'text',
						'instructions'      => 'domain.com/{rewriteURL}',
						'required'          => 0,
						'conditional_logic' => [
							[
								[
									'field'    => 'field_54adb5790c651',
									'operator' => '==',
									'value'    => '1',
								],
							],
						],
						'wrapper' => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'maxlength'     => '',
						'readonly'      => 0,
						'disabled'      => 0,
					],

					[
						'key'               => 'field_548b743712c7d',
						'label'             => 'Enable Categories & Tags',
						'name'              => 'enable_cats_tags',
						'prefix'            => '',
						'type'              => 'true_false',
						'instructions'      => 'Defaults to false',
						'required'          => 0,
						'conditional_logic' => [
							[
								[
									'field'    => 'field_54adb5790c651',
									'operator' => '==',
									'value'    => '1',
								],
							],
						],
						'wrapper' => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'message'       => 'Enable default categories and tags for custom post type.',
						'default_value' => 0
					],

					[
						'key'               => 'field_548b743712c7f',
						'label'             => 'Enable Archive',
						'name'              => 'enable_archive',
						'prefix'            => '',
						'type'              => 'true_false',
						'instructions'      => 'domain.com/{rewriteURL}',
						'required'          => 0,
						'conditional_logic' => [
							[
								[
									'field'    => 'field_54adb5790c651',
									'operator' => '==',
									'value'    => '1',
								],
							],
						],
						'wrapper' => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'message'       => 'Enables post type archives. Will use $post_type as archive slug by default.',
						'default_value' => 1,
					],
					[
						'key'               => 'field_548b74f9e3d92',
						'label'             => 'Enable Heirarchial',
						'name'              => 'enable_heirarchial',
						'prefix'            => '',
						'type'              => 'true_false',
						'instructions'      => 'The \'supports\' parameter should contain \'page-attributes\' to show the parent select box on the editor page.',
						'required'          => 0,
						'conditional_logic' => [
							[
								[
									'field'    => 'field_54adb5790c651',
									'operator' => '==',
									'value'    => '1',
								],
							],
						],
						'wrapper' => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'message'       => 'Enable post type hierarchy (e.g. page). Allows Parent to be specified.',
						'default_value' => 1,
					],
					[
						'key'               => 'field_548b7b85bfa45',
						'label'             => 'Supports',
						'name'              => 'supports',
						'prefix'            => '',
						'type'              => 'checkbox',
						'instructions'      => 'Check the ones you would like to support for your cpt.',
						'required'          => 1,
						'conditional_logic' => [
							[
								[
									'field'    => 'field_54adb5790c651',
									'operator' => '==',
									'value'    => '1',
								],
							],
						],
						'wrapper' => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'choices' => [
							'title'           => 'title',
							'editor'          => 'editor',
							'author'          => 'author',
							'thumbnail'       => 'thumbnail',
							'excerpt'         => 'excerpt',
							'trackbacks'      => 'trackbacks',
							'custom-fields'   => 'custom-fields',
							'comments'        => 'comments',
							'revisions'       => 'revisions',
							'page-attributes' => 'page-attributes',
							'post-formats'    => 'post-formats',
						],
						'default_value' => [
							'title'           => 'title',
							'editor'          => 'editor',
							'author'          => 'author',
							'thumbnail'       => 'thumbnail',
							'excerpt'         => 'excerpt',
							'trackbacks'      => 'trackbacks',
							'custom-fields'   => 'custom-fields',
							'comments'        => 'comments',
							'revisions'       => 'revisions',
							'page-attributes' => 'page-attributes',
							'post-formats'    => 'post-formats',
						],
						'layout' => 'horizontal',
					],
					[
						'key'               => 'field_54adbed9f00f0',
						'label'             => 'CPT Icon',
						'name'              => 'cpt_icon',
						'prefix'            => '',
						'type'              => 'text',
						'instructions'      => 'http://astronautweb.co/snippet/font-awesome/',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'maxlength'     => '',
						'readonly'      => 0,
						'disabled'      => 0,
					],
				],
			],
		],
		'location' => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'smcpt-settings',
				],
			],
		],
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
	]
);

endif;
