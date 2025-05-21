<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $expansion = trim($_POST['expansion'] ?? ''); // Tato hodnota přijde z formuláře
    $rarity = trim($_POST['rarity'] ?? '');
    $rating = (int)($_POST['rating'] ?? 0);

    $errors = [];

    if ($name === '') {
        $errors[] = "Jméno karty je povinné.";
    }
    if ($description === '') {
        $errors[] = "Popis karty je povinný.";
    }
    if ($image === '' || !filter_var($image, FILTER_VALIDATE_URL)) {
        $errors[] = "URL obrázku není platná.";
    }

    // Zde je klíčová změna: Hodnoty musí odpovídat 'value' atributům v HTML selectu
    $validExpansions = ['Základní', 'Klasická', 'Festival Legend', '5 Nocí Na Olomoucké'];
    if (!in_array($expansion, $validExpansions, true)) {
        $errors[] = "Neplatná expanze byla vybrána. Zvolená: " . htmlspecialchars($expansion); // Přidáno pro debug
    }

    $validRarities = ['common', 'rare', 'epic', 'legendary'];
    if (!in_array($rarity, $validRarities, true)) {
        $errors[] = "Neplatná rarita.";
    }

    if ($rating < 1 || $rating > 5) {
        $errors[] = "Hodnocení musí být mezi 1 a 5.";
    }

    if (!empty($errors)) {
        // Vylepšené zobrazení chyb, aby bylo vidět i v krčmovém stylu
        echo '<!DOCTYPE html><html lang="cs"><head><meta charset="UTF-8"><title>Chyba ve formuláři</title>';
        // Odkaz na hlavní CSS pro konzistentní vzhled
        echo '<link rel="stylesheet" href="../css/style.css">'; // Upravte cestu, pokud je potřeba
        echo '</head><body style="padding: 20px; background-color: var(--color-wood-dark); color: var(--color-parchment);">';
        echo '<div class="form-container tavern-form" style="max-width: 600px; margin: 40px auto; background-color: var(--color-parchment); color: var(--color-ink);">';
        echo '<h2 style="font-family: var(--font-subheading-medieval); color: var(--color-ink);">Ach ouvej, chyba v pergamenu!</h2>';
        foreach ($errors as $error) {
            echo "<p style='color:var(--color-highlight-red); font-weight:bold;'>- $error</p>";
        }
        echo '<p><a href="../index.php" class="tavern-button" style="text-decoration:none; display:inline-block; margin-top:20px; background-color:var(--color-wood-medium); color:var(--color-parchment);">Zpět na nástěnku</a></p>';
        echo '</div></body></html>';
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO cards (name, description, image, expansion, rarity, rating) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $image, $expansion, $rarity, $rating]);

    header('Location: ../index.php?status=success'); // Přidáno pro případné zobrazení úspěchové zprávy
    exit;
} else {
    // Pokud se někdo pokusí přistoupit k add.php přímo přes GET
    header('Location: ../index.php');
    exit;
}