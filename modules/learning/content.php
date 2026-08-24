<?php
/**
 * Learning Hub — content only. Edit titles, summaries, and quiz data here.
 */

return [

    'page' => [
        'title'    => 'NSE Learning Centre',
        'subtitle' => 'Structured modules on Kenya\'s capital markets — from first principles to active investing.',
    ],

    'tiers' => [

        'beginner' => [
            'label'       => 'Beginner',
            'subtitle'    => 'Market foundations',
            'description' => '',
            'accent'      => 'track--green',
            'lessons'     => [
                ['title' => '', 'summary' => ''],
                ['title' => '', 'summary' => ''],
                ['title' => '', 'summary' => ''],
            ],
        ],

        'intermediate' => [
            'label'       => 'Intermediate',
            'subtitle'    => 'Analysis & instruments',
            'description' => '',
            'accent'      => 'track--blue',
            'lessons'     => [
                ['title' => '', 'summary' => ''],
                ['title' => '', 'summary' => ''],
            ],
        ],

        'advanced' => [
            'label'       => 'Advanced',
            'subtitle'    => 'Strategy & portfolio',
            'description' => '',
            'accent'      => 'track--gold',
            'lessons'     => [
                ['title' => '', 'summary' => ''],
                ['title' => '', 'summary' => ''],
            ],
        ],

    ],

    'quizzes' => [

        'beginner' => [
            'title'       => 'Beginner assessment',
            'description' => '',
            'pass_mark'   => 70,
            'questions'   => [
                [
                    'text'    => '',
                    'options' => ['A' => '', 'B' => '', 'C' => '', 'D' => ''],
                    'correct' => 'A',
                ],
                [
                    'text'    => '',
                    'options' => ['A' => '', 'B' => '', 'C' => '', 'D' => ''],
                    'correct' => 'B',
                ],
            ],
        ],

        'intermediate' => [
            'title'       => 'Intermediate assessment',
            'description' => '',
            'pass_mark'   => 70,
            'questions'   => [
                [
                    'text'    => '',
                    'options' => ['A' => '', 'B' => '', 'C' => '', 'D' => ''],
                    'correct' => 'C',
                ],
            ],
        ],

        'advanced' => [
            'title'       => 'Advanced assessment',
            'description' => '',
            'pass_mark'   => 70,
            'questions'   => [
                [
                    'text'    => '',
                    'options' => ['A' => '', 'B' => '', 'C' => '', 'D' => ''],
                    'correct' => 'D',
                ],
            ],
        ],

    ],

];
