<?php include 'includes/header.php'; ?>
<?php require_once 'includes/db.php'; ?>

<body>
  <header class="page-header">
    <h1>Hearthstone Krčma: Galerie Karet</h1>
  </header>

  <div class="main-content-wrapper">
    <!-- Formulář pro přidání nové karty -->
    <div class="form-container tavern-form add-card-form">
      <h2>Přikovati novou kartu na nástěnku</h2>
      <form method="POST" action="cards/add.php">
        <div class="form-group">
          <label for="name">Jméno karty, poutníče:</label>
          <input type="text" id="name" name="name" placeholder="Např. Ragnaros Plamenometník" required />
        </div>
        <div class="form-group">
          <label for="description">Slova moudrá (popis):</label>
          <input type="text" id="description" name="description" placeholder="Např. Nemůže útočit. Na konci tvého tahu způsobí 8 poškození náhodnému nepříteli." required />
        </div>
        <div class="form-group">
          <label for="image">Obrázek z kronik (URL):</label>
          <input type="text" id="image" name="image" placeholder="https://link.k/obrazku.png" required />
        </div>
        <div class="form-group">
          <label for="expansion">Z jakého rozšíření jest?:</label>
          <select id="expansion" name="expansion" required>
            <option value="Základní">Základní</option>
            <option value="Klasická">Klasická</option>
            <option value="Festival Legend">Festival Legend</option>
            <option value="5 Nocí Na Olomoucké">5 Nocí Na Olomoucké</option>
            <!-- Přidejte další expanze podle potřeby -->
          </select>
        </div>
        <div class="form-group">
          <label for="rarity">Vzácnost artefaktu:</label>
          <select id="rarity" name="rarity" required>
            <option value="common">Obyčejný nález</option>
            <option value="rare">Vzácný kus</option>
            <option value="epic">Epický poklad</option>
            <option value="legendary">Legendární relikvie</option>
          </select>
        </div>
        <div class="form-group">
          <label for="rating">Jak ceněný jest? (1-5):</label>
          <input type="number" id="rating" name="rating" min="1" max="5" placeholder="5" required />
        </div>
        <button type="submit" class="button-primary tavern-button">Přidat do sbírky</button>
      </form>
    </div>

    <!-- Filtrovací a řadící select -->
    <div class="filter-container tavern-form">
      <label for="sort-filter">Roztřídit karty:</label>
      <select id="sort-filter">
        <option value="alpha-all">Dle jména (Všechny)</option>
        <option value="alpha-common">Obyčejné (Dle jména)</option>
        <option value="alpha-rare">Vzácné (Dle jména)</option>
        <option value="alpha-epic">Epické (Dle jména)</option>
        <option value="alpha-legendary">Legendární (Dle jména)</option>
      </select>
    </div>

    <!-- Galerie karet -->
    <div id="gallery">
      <?php
      $cards = $pdo->query("SELECT * FROM cards ORDER BY LOWER(name) ASC")->fetchAll();

      foreach ($cards as $card) {
        echo '<div class="gallery-item">'; // Obal pro kartu a stuhu
        
        // Karta samotná
        echo '<div class="card old-parchment" data-rarity="' . htmlspecialchars($card['rarity']) . '" data-name="' . htmlspecialchars(strtolower($card['name'])) . '">';
        echo '<img src="' . htmlspecialchars($card['image']) . '" alt="' . htmlspecialchars($card['name']) . '">';
        echo '<div class="card-content">';
        echo '<h3>' . htmlspecialchars($card['name']) . '</h3>';
        echo '<p class="card-description">' . htmlspecialchars($card['description']) . '</p>';
        echo '<p class="card-expansion"><strong>Rozšíření:</strong> ' . htmlspecialchars($card['expansion']) . '</p>';
        
        // ZDE JE ZMĚNA HODNOCENÍ
        echo '<div class="card-rating">' . str_repeat('🍺', intval($card['rating'])) . '</div>'; 

        echo '<form class="delete-form" method="POST" action="cards/delete.php" onsubmit="return confirm(\'Vpravdě chcete tento svitek spálit?\');">';
        echo '<input type="hidden" name="id" value="' . $card['id'] . '">';
        echo '<button type="submit" class="button-delete tavern-button">Sejmouti z klínce</button>';
        echo '</form>';

        echo '</div>'; // konec .card-content
        echo '</div>'; // konec .card
        
        // Stuha rarity (nyní napravo)
        echo '<div class="rarity-ribbon rarity-ribbon-' . htmlspecialchars($card['rarity']) . '"></div>';

        echo '</div>'; // konec .gallery-item
      }
      ?>
    </div>
  </div> <!-- konec main-content-wrapper -->

  <footer class="page-footer">
    <p>© <?php echo date("Y"); ?> Krčma U Malého Vidláka. Všechny karty pečlivě archivovány.</p>
  </footer>
  <!-- Připojení externího JavaScriptu -->
  <script src="scripts/filter.js"></script>

<?php include 'includes/footer.php'; ?>
</body>