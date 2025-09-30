<?php

if (!defined('ABSPATH')) {
	die('-1');
}

function accordion($atts, $content = null)
{
	$atts = shortcode_atts([
		'text_fields' => '',
		'heading' => '',
		'id' => '',
	], $atts);

	budi_add_style('accordion', this_dir_url(__FILE__) . 'style.css');

	$decoded_array = urldecode($atts['text_fields']);
	$php_array = json_decode($decoded_array, true);

	$content = array();

	foreach ($php_array as $item) {
		$content[] = [
			"question" => $item['text'],
			"answer" => $item['field_answer'],
		];
	}

	$uniqid = uniqid();

	ob_start();


	?>

    <section id="acc_id<?php echo $uniqid; ?>">
        <div class="headline">
            <h3><?= $atts['heading']; ?></h3>
            <div class="linie"></div>
            <div class="pfeil"><svg class="svg_pfeil" xmlns="http://www.w3.org/2000/svg" width="25" height="40" fill="#006b2d" viewBox="0 0 11.475 7.05">
                    <path id="chevron-right-solid" d="M64.886,43.48a.912.912,0,0,1-.623-.24.78.78,0,0,1,0-1.159l4.665-4.338L64.263,33.4a.78.78,0,0,1,0-1.159.928.928,0,0,1,1.246,0L70.8,37.163a.78.78,0,0,1,0,1.159L65.509,43.24A.909.909,0,0,1,64.886,43.48Z" transform="translate(43.48 -64.005) rotate(90)" />
                </svg>
            </div>
        </div>
        <div class="question_conteiner">
			<?php foreach ($content as $item) : ?>
                <div class="accordion">
                    <h4 class="accordion_question"><?= $item['question']; ?></h4>
                    <p class="accordion_answer"><?= rawurldecode(base64_decode($item['answer'])); ?></p>
                </div>
			<?php endforeach; ?>
        </div>
    </section>

    <style>
        .accordion {
            width: 100%;
            margin-bottom: 12px;
        }

        .headline {
            margin-bottom: 0.5rem;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .pfeil {
            cursor: pointer;
            transition: all 500ms;
        }

        .headline.active .pfeil {
            transform: rotate(180deg);
            cursor: pointer;
            transition: all 500ms;
        }

        .headline h3 {
            color: black;
            font-size: 24px;
            width: fit-content;
            padding: 5px;
            align-items: center;
            /*display: flex;*/
            cursor: pointer;
            /*justify-content: space-between;*/
            text-align: left;
            text-wrap: nowrap;
            margin: 0;
        }

        .linie {
            border-bottom: 2px solid;
            color: #FCDC05;
            position: relative;
            width: 100%;
            margin-right: 20px;
            margin-left: 10px;
            position: relative;
            top: 2px;
            cursor: pointer;
        }



        h4.accordion_question {
            padding: 5px;
            font-size: 22px;
            font-weight: 400;
            color: #666;
            background-color: #f8f8f8;
            border: 1px solid #f1f1f1;
            cursor: pointer;
            /*transition: all 0.2s;*/
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-left: 20px;

            &:after {
                content: '\002b';
                display: inline-block;
                font-size: 25px;
                margin: 10px;
                color: #006b2d;
            }

        }

        h4,
        .h4 {
            margin: 0;
        }

        .accordion_question.active {
            &:after {
                content: '\002d';
                color: #006b2d;
            }
        }


        .accordion_answer {
            display: none;
            padding: 15px;
            height: auto;
            background-color: #f8f8f8;

        }

        p.accordion_answer {
            background-color: f8f8f8;
        }

        .accordion_answer p {
            margin-bottom: 0;
        }

        @media(max-width:585px) {
            .headline h3 {
                font-size: 21px;
            }

            h4.accordion_question {
                font-size: 19px;
            }

            p.accordion_answer {
                font-size: 17px;
            }

            .svg_pfeil {
                width: 20px;
            }
        }

        @media(max-width:505px) {
            .headline h3 {
                font-size: 19px;
            }

            h4.accordion_question {
                font-size: 17px;
            }

            p.accordion_answer {
                font-size: 15px;
            }

            .svg_pfeil {
                width: 15px;
            }
        }

        @media(max-width:475px) {
            .headline h3 {
                font-size: 16px;
            }

            h4.accordion_question {
                font-size: 14px;
            }

            p.accordion_answer {
                font-size: 12x;
            }

            .svg_pfeil {
                width: 13px;
            }
        }

        @media(max-width:368px) {
            .headline h3 {
                font-size: 14px;
            }

            h4.accordion_question {
                font-size: 12px;
            }

            p.accordion_answer {
                font-size: 10px;
            }

            .svg_pfeil {
                width: 10px;
            }
        }

        @media(max-width:335px) {
            .headline h3 {
                font-size: 12px;
            }

            h4.accordion_question {
                font-size: 10px;
            }

            p.accordion_answer {
                font-size: 8px;
            }

            .svg_pfeil {
                width: 8px;
            }
        }

        @media(max-width:306px) {
            .headline h3 {
                font-size: 10px;
            }

            h4.accordion_question {
                font-size: 8px;
            }

            p.accordion_answer {
                font-size: 6px;
            }

            .svg_pfeil {
                width: 6px;
            }
        }
    </style>

    <!-- Hier kommt der HTML Output hin  -->

    <script>
        jQuery('#acc_id<?php echo $uniqid; ?> .question_conteiner').hide();
        jQuery(document).ready(function() {
            jQuery('#acc_id<?php echo $uniqid; ?> .headline').click(function() {
                jQuery(this).toggleClass('active');
                jQuery('#acc_id<?php echo $uniqid; ?> .headline').not(this).removeClass('active');
                jQuery(this).next('#acc_id<?php echo $uniqid; ?> .question_conteiner').slideToggle();
            });

            jQuery('#acc_id<?php echo $uniqid; ?> h4').click(function() {
                jQuery(this).toggleClass('active');
                jQuery(this).next('#acc_id<?php echo $uniqid; ?> p').slideToggle();

                jQuery('#acc_id<?php echo $uniqid; ?> h4').not(this).removeClass('active');
                jQuery('#acc_id<?php echo $uniqid; ?> p').not(jQuery(this).next()).slideUp();
            });

        });
    </script>

	<?php


	$output = ob_get_contents();
	ob_end_clean();


	return $output;
}

add_shortcode('accordion', 'accordion');


if (function_exists('vc_map')) {
	add_action('vc_before_init', function () {
		vc_map(array(
			"name" => "Akkordion",
			"description" => "",
			"base" => "accordion",
			"class" => "",
			"icon" => get_template_directory_uri() . "/shortcodes/bundesweit.digital.png",
			"category" => "bundesweit.digital",
			"content_element" => true,
			"holder" => "div",
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => "Überschrift",
					"param_name" => "heading",
					"discription" => "",
				),
				array(
					"type" => "param_group",
					"heading" => "Add new Inhalt",
					"param_name" => "text_fields",
					"description" => "",
					"params" => array(
						array(
							"type" => "textfield",
							"heading" => "Fragen",
							"param_name" => "text",
							"description" => "",
						),
						array(
							"type" => "textarea_raw_html",
							"heading" => "Antwort",
							"param_name" => "field_answer",
							"description" => "",
						),

					)
				),
			)

		));
	});
}
