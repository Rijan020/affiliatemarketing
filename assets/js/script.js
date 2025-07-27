document.addEventListener('DOMContentLoaded', function() {
  const productsContainer = document.querySelector('.products');
  if (!productsContainer) return;

  async function loadProducts() {
    try {
      const response = await fetch('includes/fetch_products.php');
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      if (!data.success) {
        throw new Error(data.error || 'Failed to load products');
      }
      renderProducts(data.data);
    } catch (error) {
      console.error('Error loading products:', error);
      productsContainer.innerHTML = `
        <div class="error">
          Failed to load products. Please try again later.
          <br><small>${error.message}</small>
        </div>
      `;
    }
  }

  function escapeHTML(str) {
    return str.replace(/[&<>"']/g, function(m) {
      return {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;'}[m];
    });
  }

  function renderProducts(products) {
    if (!products || products.length === 0) {
      productsContainer.innerHTML = '<p>No products available at the moment.</p>';
      return;
    }

    productsContainer.innerHTML = products.map(product => `
      <div class="product">
        <img src="${escapeHTML(product.image_url)}" alt="${escapeHTML(product.name)}" loading="lazy" />
        <div class="product-info">
          <h3>${escapeHTML(product.name)}</h3>
          <p>${escapeHTML(product.description)}</p>
          <p class="price">
            <span class="original">Rs.${escapeHTML(product.price_original)}</span>
            <span class="discounted">Rs.${escapeHTML(product.price_discounted)}</span>
          </p>
          <a href="${escapeHTML(product.affiliate_url)}" class="button" target="_blank" rel="noopener noreferrer">Buy Now</a>
        </div>
      </div>
    `).join('');
  }

  loadProducts();
});
