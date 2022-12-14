<?php
namespace QyberTech\Presenter;


class HtmlFormatter
{
	/**
	 * Formats HTML by re-indenting the tags and removing unnecessary whitespace.
	 *
	 * @param string $html HTML string.
	 * @param string $indentWith Characters that are being used for indentation (default = 4 spaces).
	 * @param string $tagsWithoutIndentation Comma-separated list of HTML tags that should not be indented (default = html,link,img,meta)
	 * @return string Re-indented HTML.
	 */
	public static function format($html, $indentWith = '	', $tagsWithoutIndentation = ['html','base','col','input','link','img','meta','br','hr','embed','source'])
	{
		// replace newlines (CRLF and LF), followed by a non-whitespace character, with a space
		$html = preg_replace('/\\r?\\n([^\s])/', ' $1', $html);

		// remove all remaining line feeds and replace tabs with spaces
		$html = str_replace(["\n", "\r", "\t"], ['', '', ' '], $html);
		$elements = preg_split('/(<.+>)/U', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		$dom = self::parseDom($elements);

		$indent = 0;
		$output = array();
		foreach ($dom as $index => $element)
		{
			if ($element['opening'])
			{
				$output[] = "\n".str_repeat($indentWith, $indent).trim($element['content']);

				// make sure that only the elements who have not been blacklisted are being indented
				if ( ! in_array($element['type'], $tagsWithoutIndentation))
				{
					++$indent;
				}
			}
			else if ($element['standalone'])
			{
				$output[] = "\n".str_repeat($indentWith, $indent).trim($element['content']);
			}
			else if ($element['closing'])
			{
				//$indent cannot be less than zero
				if ($indent>0) --$indent;
				$lf = "\n".str_repeat($indentWith, abs($indent));
				if (isset($dom[$index - 1]) && $dom[$index - 1]['type']==$element['type'] && $dom[$index - 1]['opening'])
				{
					$lf = '';
				}
				$output[] = $lf.trim($element['content']);
			}
			else if ($element['text'])
			{
				//~ $output[] = "\n".str_repeat($indentWith, $indent).trim($element['content']);
				$output[] = "\n".str_repeat($indentWith, $indent).preg_replace('/ [ \t]*/', ' ', $element['content']);
			}
			else if ($element['comment'])
			{
				$output[] = "\n".str_repeat($indentWith, $indent).trim($element['content']);
			}
		}

		return trim(implode('', $output));
	}

	/**
	 * Parses an array of HTML tokens and adds basic information about about the type of
	 * tag the token represents.
	 *
	 * @param Array $elements Array of HTML tokens (tags and text tokens).
	 * @return Array HTML elements with extra information.
	 */
	public static function parseDom(Array $elements)
	{
		$dom = array();
		foreach ($elements as $element)
		{
			if (!$currentElement = trim($element)) continue;

			$isText = false;
			$isComment = false;
			$isClosing = false;
			$isOpening = false;
			$isStandalone = false;

			// comment
			if (strpos($currentElement, '<!') === 0)
			{
				$isComment = true;
			}
			// closing tag
			else if (strpos($currentElement, '</') === 0)
			{
				$isClosing = true;
			}
			//~ // stand-alone tag
			//~ else if (preg_match('/\/>$/', $currentElement))
			//~ {
				//~ $isStandalone = true;
			//~ }

			// stand-alone tag - optimisation
			else if (substr($currentElement, -2) == '/>')
			{
				$isStandalone = true;
			}
			// normal opening tag
			else if (strpos($currentElement, '<') === 0)
			{
				$isOpening = true;
			}
			// text
			else
			{
				$isText = true;
			}

			$dom[] = array(
				'text' 				=> $isText,
				'comment'			=> $isComment,
				'closing'	 		=> $isClosing,
				'opening'	 		=> $isOpening,
				'standalone'	 	=> $isStandalone,
				'content'			=> $element,
				'type'				=> preg_replace('/^<\/?(\w+)[ >].*$/U', '$1', $element)
			);
		}
		return $dom;
	}
}
