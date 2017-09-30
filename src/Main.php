<?php

namespace Touriends\Backend;

class Main {
	public $classes = [];
	public function main() {
		add_action('init', function() {
			//Post\Place::init();
			//AJAX\Demo::init();
			AJAX\Member::init();
			AJAX\Tour::init();
			AJAX\Recommend::init();
		});

		add_action('admin_enqueue_scripts', function() {
			AJAX\Matching::getMatching();
		});
	}
}
