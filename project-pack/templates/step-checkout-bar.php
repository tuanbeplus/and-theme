<?php 
$contact_icon = '<svg version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve"> <path class="puchipuchi_een" d="M26,1H6C4.895,1,4,1.895,4,3H3C2.448,3,2,3.448,2,4s0.448,1,1,1h1v2H3C2.448,7,2,7.448,2,8 s0.448,1,1,1h1v2H3c-0.552,0-1,0.448-1,1s0.448,1,1,1h1v2H3c-0.552,0-1,0.448-1,1s0.448,1,1,1h1v2H3c-0.552,0-1,0.448-1,1 s0.448,1,1,1h1v2H3c-0.552,0-1,0.448-1,1s0.448,1,1,1h1v2H3c-0.552,0-1,0.448-1,1s0.448,1,1,1h1c0,1.105,0.895,2,2,2h20 c1.105,0,2-0.895,2-2V3C28,1.895,27.105,1,26,1z M16,7c1.105,0,2,0.895,2,2c0,1.105-0.895,2-2,2s-2-0.895-2-2 C14,7.895,14.895,7,16,7z M16,12c1.657,0,3,1.343,3,3c0,1.128-1.343,1-3,1s-3,0-3-1C13,13.343,14.343,12,16,12z M20,24h-8 c-0.552,0-1-0.448-1-1s0.448-1,1-1h8c0.552,0,1,0.448,1,1S20.552,24,20,24z M22,21H10c-0.552,0-1-0.448-1-1s0.448-1,1-1h12 c0.552,0,1,0.448,1,1S22.552,21,22,21z"/> </svg>';
$checkout_icon = '<svg viewBox="0 0 52 52" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"><path d="M38.67,27.35A11.33,11.33,0,1,1,27.35,38.67h0A11.34,11.34,0,0,1,38.67,27.35ZM20.36,37.63a4,4,0,1,1-4,4v0A4,4,0,0,1,20.36,37.63ZM42.8,34.07l-6.06,6.79L34,38.09a.79.79,0,0,0-1.11,0l0,0-1.11,1.07a.7.7,0,0,0-.07,1l.07.08L35.6,44a1.62,1.62,0,0,0,1.14.48A1.47,1.47,0,0,0,37.87,44l7.19-7.87a.83.83,0,0,0,0-1l-1.12-1.05a.79.79,0,0,0-1.11,0ZM8.2,2a2.42,2.42,0,0,1,2.25,1.7h0l.62,2.16H46.36A1.5,1.5,0,0,1,47.9,7.31a1.24,1.24,0,0,1-.06.47h0L43.66,22.43a1.42,1.42,0,0,1-.52.82,16.42,16.42,0,0,0-4.47-.64,16,16,0,0,0-5.47,1H19.36a2.2,2.2,0,0,0-2.22,2.18,2.11,2.11,0,0,0,.13.75h0v.08a2.26,2.26,0,0,0,2.17,1.62h7.1a16,16,0,0,0-2.77,4.61H16a2.32,2.32,0,0,1-2.25-1.7h0L6.5,6.62H4.33A2.37,2.37,0,0,1,2,4.22V4.16A2.46,2.46,0,0,1,4.48,2H8.2Z"/></svg>';

$steps = [
  [
    "name" => "add_attendees",
    "label" => __("Add Attendees", "pp"),
    "icon" => $contact_icon,
    "active" => true,
  ],
  [
    "name" => "checkout",
    "label" => __("Checkout", "pp"),
    "icon" => $checkout_icon,
    "active" => false,
  ],
];
?>
<div class="step-checkout-bar">
  <ul class="step-list">
    <li class="__line"></li>
  <?php foreach($steps as $index => $step) : 
    $_active = ($step['active'] == true) ? '__active' : '';
    $_classes = ['step-item', '__step-' . $step['name'], $_active];  
  ?>
    <li class="<?php echo implode(' ', $_classes); ?>">
      <span class="__icon"><?php echo $step['icon'] ?></span>
      <span class="__label"><?php echo sprintf('%s — %s', 'Step ' . $index + 1, $step['label']) ?></span>
    </li>
    <li class="__line"></li>
  <?php endforeach; ?>
  </ul>
</div>