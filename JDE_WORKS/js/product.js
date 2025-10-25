// Products page functionality (extracted from product.php)

document.addEventListener('DOMContentLoaded', function() {
  setupProductAnimations();
});

function setupProductAnimations() {
  var observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
  var observer = new IntersectionObserver(function(entries){
    entries.forEach(function(entry, index){
      if (entry.isIntersecting) {
        setTimeout(function(){
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }, index * 100);
      }
    });
  }, observerOptions);

  var productItems = document.querySelectorAll('.product-item');
  productItems.forEach(function(item){
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(item);
  });
}

function orderProduct(productId) {
  var productName = getProductName(productId);
  document.getElementById('modalProductName').innerHTML = "You're about to order: <strong>" + productName + "</strong>";
  document.getElementById('orderModal').classList.add('active');
}

function closeModal() {
  document.getElementById('orderModal').classList.remove('active');
}

function proceedToOrder() {
  closeModal();
  window.location.href = 'ordering.html';
}

function getProductName(productId) {
  var productNames = {
    'mens-polo-uniform': "Men's Polo Uniform",
    'mens-trouser': "Men's Trouser",
    'womens-blouse-uniform': "Women's Blouse Uniform",
    'womens-skirt': "Women's Skirt",
    'womens-pants': "Women's Pants",
    'mens-uniform': "Men's Uniform Set",
    'custom-mens-polo': "Custom Men's Polo Uniform",
    'custom-mens-trouser': "Custom Men's Trouser",
    'custom-womens-blouse': "Custom Women's Blouse Uniform"
  };
  return productNames[productId] || 'Selected Product';
}
