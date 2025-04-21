jQuery(document).ready(function ($) {
  function cmcwUpdateCartCount() {
    $.ajax({
      url: cmcwCount.cmcw_ajax_url,
      type: "POST",
      data: { action: "cmcw_update_cart_count" },
      success: function (response) {
        $(".cmcw-cart-count").text(response.count);
      },
    });
  }

  // Update cart count when the cart is updated
  $(document.body).on(
    "added_to_cart removed_from_cart updated_wc_div",
    function () {
      cmcwUpdateCartCount();
    }
  );

  // Initial load update
  cmcwUpdateCartCount();

  // Sidebar toggle

  $(".cmcw-shortcode-container").on("mouseenter", function () {
    $("#cmcw-cart-sidebar").addClass("cmcw-opened");
    // loadCartContents();
  });

  $("#cmcw-cart-close").on("click", function () {
    $("#cmcw-cart-sidebar").removeClass("cmcw-opened");
  });

  // Disable link when inside the cart sidebar
  const CmcwSidebar = document.getElementById("cmcw-cart-sidebar");
  const CmcwParentLink = CmcwSidebar.closest("a"); // Get the nearest parent <a>

  if (CmcwParentLink) {
    CmcwSidebar.addEventListener("mouseenter", function () {
      CmcwParentLink.dataset.href = CmcwParentLink.getAttribute("href"); // store the href
      CmcwParentLink.removeAttribute("href"); // disable it
    });

    CmcwSidebar.addEventListener("mouseleave", function () {
      CmcwParentLink.setAttribute("href", CmcwParentLink.dataset.href); // restore href
      delete CmcwParentLink.dataset.href;
      $("#cmcw-cart-sidebar").removeClass("cmcw-opened"); // close the sidebar
    });
  }
});
