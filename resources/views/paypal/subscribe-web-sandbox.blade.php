<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top" id="paypalform">
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="business" value="EL28KWY8LJKVA">
    <input type="hidden" name="lc" value="US">
    <input type="hidden" name="item_name" value="{{$package['name']}}">
    <input type="hidden" name="item_number" value="{{$package['package_id']}}">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="rm" value="1">
    <input type="hidden" name="return" value="https://hobojet.smc-ol.com/paypal/success">
    <input type="hidden" name="cancel_return" value="https://hobojet.smc-ol.com/paypal/cancel">
    <input type="hidden" name="src" value="1">
    <input type="hidden" name="a3" value="{{$package['price']}}">
    <input type="hidden" name="p3" value="1">
    <input type="hidden" name="t3" value="M">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHosted">
    {{--<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">--}}
    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

{{--
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="business" value="EL28KWY8LJKVA">
    <input type="hidden" name="lc" value="US">
    <input type="hidden" name="item_name" value="Package">
    <input type="hidden" name="item_number" value="11">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="rm" value="1">
    <input type="hidden" name="return" value="https://hobojet.smc-ol.com/paypal/success">
    <input type="hidden" name="cancel_return" value="https://hobojet.smc-ol.com/paypal/cancel">
    <input type="hidden" name="src" value="1">
    <input type="hidden" name="a3" value="11.00">
    <input type="hidden" name="p3" value="1">
    <input type="hidden" name="t3" value="M">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHosted">
    <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
--}}