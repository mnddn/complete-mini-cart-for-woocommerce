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
});
