<input class="btn-paypal btn" type="image" ng-click="checkout()" src="{{url('images/paypall-btn.png')}}" border="0" alt="PayPal - The safer, easier way to pay online!">

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top" id="paypalbookform">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="EL28KWY8LJKVA">
    <input type="hidden" name="lc" value="US">

    <input type="hidden" name="item_name" value="Flight">
    {{--<input type="hidden" name="item_number" value="1">--}}
    {{--<input type="hidden" name="amount" value="1.00">--}}

    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="button_subtype" value="services">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="rm" value="1">
    <input type="hidden" name="return" value="https://hobojet.smc-ol.com/paypal/success">
    <input type="hidden" name="cancel_return" value="https://hobojet.smc-ol.com/paypal/cancel">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
    <input type="hidden" name="paymentaction" value="authorization">
    {{--<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">--}}
    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

{{--
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="EL28KWY8LJKVA">
    <input type="hidden" name="lc" value="US">
    <input type="hidden" name="item_name" value="Flight">
    <input type="hidden" name="item_number" value="1">
    <input type="hidden" name="amount" value="1.00">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="button_subtype" value="services">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="rm" value="1">
    <input type="hidden" name="return" value="https://hobojet.smc-ol.com/paypal/success">
    <input type="hidden" name="cancel_return" value="https://hobojet.smc-ol.com/paypal/cancel">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
    <input type="hidden" name="paymentaction" value="authorization">
    <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
--}}