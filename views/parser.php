<?php
// Функція-обробник початкових тегів
function startElement($parser, $name, $attrs) {
    global $html;
    $html .= "<$name";
    foreach ($attrs as $key => $value) {
        $html .= " $key=\"$value\"";
    }
    $html .= ">";
}

// Функція-обробник кінцевих тегів
function endElement($parser, $name) {
    global $html;
    $html .= "</$name>";
}

// Функція-обробник текстового вмісту
function characterData($parser, $data) {
    global $html;
    // Перевірка, чи є текст URL-адресою
    if (filter_var(trim($data), FILTER_VALIDATE_URL)) {
        // Якщо це URL, обернути його в тег <a>
        $html .= '<a href="' . htmlspecialchars(trim($data)) . '">' . htmlspecialchars(trim($data)) . '</a>';
    } else {
        // Інакше просто додати текст
        $html .= htmlspecialchars($data);
    }
}

// Функція для парсингу XML контенту та повернення HTML
function parseXMLToHTML($xmlContent) {
    global $html;
    $html = '';

    // Ініціалізація парсера
    $parser = xml_parser_create();

    // Реєстрація функцій-обробників
    xml_set_element_handler($parser, "startElement", "endElement");
    xml_set_character_data_handler($parser, "characterData");

    // Парсинг XML
    if (!xml_parse($parser, $xmlContent, true)) {
        die(sprintf(
            "XML Error: %s at line %d",
            xml_error_string(xml_get_error_code($parser)),
            xml_get_current_line_number($parser)
        ));
    }

    // Завершення роботи парсера
    xml_parser_free($parser);

    // Повернення згенерованого HTML
    return $html;
}
?>
