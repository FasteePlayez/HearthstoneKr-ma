document.addEventListener('DOMContentLoaded', function () {
    const sortFilterSelect = document.getElementById('sort-filter');
    const gallery = document.getElementById('gallery');
    
    // Načteme všechny položky galerie (.gallery-item) jednou při startu
    const allGalleryItems = Array.from(gallery.querySelectorAll('.gallery-item'));

    if (sortFilterSelect && gallery) {
        sortFilterSelect.addEventListener('change', function () {
            const selectedValue = this.value; // např. "alpha-all", "alpha-rare"
            
            let itemsToDisplay = [...allGalleryItems]; // Vytvoříme pracovní kopii všech položek

            // 1. Filtrování podle rarity (pokud není vybráno "...-all")
            if (!selectedValue.endsWith('-all')) {
                const rarityToFilter = selectedValue.split('-')[1]; // z "alpha-rare" získáme "rare"
                itemsToDisplay = itemsToDisplay.filter(item => {
                    const cardElement = item.querySelector('.card');
                    return cardElement && cardElement.dataset.rarity === rarityToFilter;
                });
            }

            // 2. Řazení (vždy abecedně podle data-name na .card)
            // data-name by mělo být v PHP generováno jako normalizovaný string (např. malá písmena)
            itemsToDisplay.sort((itemA, itemB) => {
                const cardA = itemA.querySelector('.card');
                const cardB = itemB.querySelector('.card');

                const nameA = cardA ? (cardA.dataset.name || '') : '';
                const nameB = cardB ? (cardB.dataset.name || '') : '';
                
                return nameA.localeCompare(nameB); // Použijeme localeCompare pro správné abecední řazení
            });

            // 3. Aktualizace galerie v DOM
            // Vyčistíme aktuální obsah galerie
            gallery.innerHTML = ''; 
            
            // Přidáme seřazené a filtrované položky zpět do galerie
            itemsToDisplay.forEach(item => {
                gallery.appendChild(item);
            });
        });

        // Volitelné: Pokud chcete, aby se filtr/řazení aplikovalo hned po načtení
        // na základě výchozí hodnoty selectu (což je "alpha-all", takže se jen seřadí).
        // PHP již řadí data abecedně, takže toto by bylo redundantní pro první načtení,
        // ale může být užitečné, pokud by select měl jinou výchozí hodnotu.
        // sortFilterSelect.dispatchEvent(new Event('change')); 
    }
});