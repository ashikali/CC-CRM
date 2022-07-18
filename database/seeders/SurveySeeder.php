<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use MattDaneshvar\Survey\Models\Survey;


class SurveySeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

	$survey = Survey::create(['name' => 'Your feedback is important to us','settings' => ['accept-guest-entries' => true]]);
	$one = $survey->sections()->create(['name' => 'About the Agent']);
	$one->questions()->create([
	    'content' => 'Kindly share your experience with the agent',
	    'type' => 'radio',
	    'rules' => ['sometimes', 'in:Satisfied,Not Satisfied'],
	    'options' => ['Satisfied', 'Not Satisfied']
	]); 

	$two = $survey->sections()->create(['name' => 'Overall Experience']);
	$two->questions()->create([
	    'content' => 'How was the Overall Experience ?',
	    'type' => 'radio',
	    'rules' => ['sometimes', 'in:Satisfied,Not Satisfied'],
	    'options' => ['Satisfied', 'Not Satisfied']
	]);

	$three = $survey->sections()->create(['name' => 'Comments']);
	$three->questions()->create([
	    'content' => 'Kindly Share your comments',
	    'type' => 'textarea',
	    'rules' => ['sometimes']
	]);


    }

}
