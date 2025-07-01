<?php

class Interest extends MY_Controller {

	public function index()
	{
		$this->show_my_response([
			[
				"id" => 1,
				"slug" => "interest_music",
				"label" => "Music",
			],
			[
				"id" => 2,
				"slug" => "interest_movies",
				"label" => "Movies",
			],
			[
				"id" => 3,
				"slug" => "interest_television",
				"label" => "Television",
			],
			[
				"id" => 4,
				"slug" => "interest_gaming",
				"label" => "Gaming",
			],
			[
				"id" => 5,
				"slug" => "interest_articles",
				"label" => "Articles",
			],
			[
				"id" => 6,
				"slug" => "interest_life_events",
				"label" => "Live Events",
			]
		]);
	}

}