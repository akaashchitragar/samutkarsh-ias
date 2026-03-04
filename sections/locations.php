<?php
// Fetch active centers from DB (schema: name, address, city, pincode, phone, manager only)
$loc_centers = [];
if (isset($conn) && $conn) {
    try {
        $result = $conn->query(
            "SELECT name, address, city, pincode, phone, manager FROM centers WHERE status = 'active' ORDER BY city ASC"
        );
        if ($result && $result->num_rows > 0) {
            $colors = ['#EA580C', '#2563EB', '#7C3AED', '#16A34A', '#DB2777', '#0891B2', '#059669', '#DC2626', '#EA580C'];
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $loc_centers[] = [
                    'city'          => $row['city'],
                    'phone'         => preg_replace('/^\+91\s?/', '', $row['phone']),
                    'phone_link'    => '+' . preg_replace('/[^0-9]/', '', $row['phone']),
                    'contact'       => $row['manager'] ?? '',
                    'contact_person'=> $row['manager'] ?? '',
                    'designation'   => 'Centre Coordinator',
                    'type'          => (stripos($row['name'] ?? '', 'Main') !== false || stripos($row['name'] ?? '', 'Hubballi') !== false) ? 'Head Office' : 'Branch Centre',
                    'color'         => $colors[$i % count($colors)],
                ];
                $i++;
            }
        }
    } catch (Exception $e) {
        error_log('Locations DB query failed: ' . $e->getMessage());
        $loc_centers = [];
    }
}

$main_centers = [
    [
        'city'        => 'Hubballi',
        'address'     => 'KLE Tech, BVB Campus, Vidyanagar, Hubballi — 580031',
        'phone'       => '96634 24767 / 99861 16934',
        'phone_link'  => '+919663424767',
        'contact'     => 'Shri. Kirankumar Chitragar',
        'designation' => 'Centre Head',
        'type'        => 'Head Office',
        'color'       => '#EA580C',
        'color_light' => '#FFF7ED',
    ],
    [
        'city'        => 'Bengaluru',
        'address'     => 'Shanders Group, 5th Block, 1097, 18th B Main Rd, Rajajinagar, Bengaluru — 560010',
        'phone'       => '95918 55055',
        'phone_link'  => '+919591855055',
        'contact'     => 'Centre Coordinator',
        'designation' => 'Branch Head',
        'type'        => 'Branch Centre',
        'color'       => '#2563EB',
        'color_light' => '#EFF6FF',
    ],
];

$network_centers = [
    ['city' => 'Hubballi',           'phone' => '96634 00284', 'phone_link' => '+919663400284', 'contact' => 'Shri. Kirankumar Chitragar', 'designation' => 'Centre Coordinator', 'type' => 'Head Office',    'color' => '#EA580C'],
    ['city' => 'Belagavi',           'phone' => '96118 23047', 'phone_link' => '+919611823047', 'contact' => 'Smt. Pratibha Patil',        'designation' => 'Centre Coordinator', 'type' => 'Branch Centre', 'color' => '#7C3AED'],
    ['city' => 'Sirsi',              'phone' => '98450 44860', 'phone_link' => '+919845044860', 'contact' => 'Shri. Lokesh',               'designation' => 'Centre Coordinator', 'type' => 'Branch Centre', 'color' => '#16A34A'],
    ['city' => 'Kumta',              'phone' => '87621 52056', 'phone_link' => '+918762152056', 'contact' => 'Smt. Rekha Hegde',           'designation' => 'Centre Coordinator', 'type' => 'Branch Centre', 'color' => '#DB2777'],
    ['city' => 'Hagari Bommanhalli', 'phone' => '89042 70616', 'phone_link' => '+918904270616', 'contact' => 'Smt. Savita Ramesh',         'designation' => 'Centre Coordinator', 'type' => 'Branch Centre', 'color' => '#2563EB'],
    ['city' => 'Bellari',            'phone' => '98451 20397', 'phone_link' => '+919845120397', 'contact' => 'Shri. Shivkumar',            'designation' => 'Centre Coordinator', 'type' => 'Branch Centre', 'color' => '#DC2626'],
    ['city' => 'Gangavati',          'phone' => '90190 50606', 'phone_link' => '+919019050606', 'contact' => 'Shri. Veeru Kotagi',          'designation' => 'Centre Coordinator', 'type' => 'Branch Centre', 'color' => '#0891B2'],
    ['city' => 'Raichuru',           'phone' => '86602 77229', 'phone_link' => '+918660277229', 'contact' => 'Smt. Arunajyothi',           'designation' => 'Centre Coordinator', 'type' => 'Branch Centre', 'color' => '#059669'],
];

if (!empty($loc_centers)) $network_centers = $loc_centers;
?>

<section id="locations" style="background:var(--slate-50);">
  <div class="container" style="max-width:1280px;">

    <!-- Header -->
    <div class="text-center mb-5" style="max-width:640px;margin-left:auto;margin-right:auto;">
      <span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3"
        style="background:#FFF7ED;border:1px solid #FFEDD5;color:var(--primary);font-size:0.75rem;font-weight:600;">
        <i class="ri-building-2-line"></i> Our Network
      </span>
      <h2 class="section-title mb-3">Visit Our <span class="text-primary">Centres Across Karnataka</span></h2>
      <p style="color:var(--slate-600);font-size:0.9rem;line-height:1.8;">
        With 16 centres of excellence across Karnataka, we bring quality civil services preparation closer to aspiring candidates in every region.
      </p>
    </div>

    <!-- Main Centres -->
    <div class="mb-5">
      <div class="d-flex align-items-center gap-3 mb-4">
        <span class="fw-bold" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--slate-400);">Main Centres</span>
        <div style="flex:1;height:1px;background:var(--slate-200);"></div>
      </div>

      <div class="row g-4">
        <?php foreach ($main_centers as $mc): ?>
        <div class="col-lg-6">
          <div class="bg-white rounded-4 p-0 overflow-hidden" style="border:1px solid var(--slate-100);box-shadow:0 4px 20px rgba(15,23,42,0.06);">
            <!-- Coloured header band -->
            <div class="d-flex align-items-center justify-content-between px-4 py-3"
              style="background:<?php echo $mc['color_light']; ?>;border-bottom:1px solid <?php echo $mc['color']; ?>22;">
              <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-3"
                  style="width:40px;height:40px;background:<?php echo $mc['color']; ?>;">
                  <i class="ri-map-pin-2-line" style="color:#fff;font-size:1.1rem;"></i>
                </div>
                <div>
                  <h4 class="fw-bold mb-0" style="font-size:1rem;color:var(--slate-900);"><?php echo htmlspecialchars($mc['city']); ?></h4>
                  <span style="font-size:0.72rem;color:<?php echo $mc['color']; ?>;font-weight:600;"><?php echo htmlspecialchars($mc['designation']); ?></span>
                </div>
              </div>
              <span class="fw-bold px-2 py-1 rounded-2" style="font-size:0.68rem;letter-spacing:0.05em;text-transform:uppercase;background:<?php echo $mc['color']; ?>;color:#fff;">
                <?php echo $mc['type'] === 'Head Office' ? 'Head Office' : 'Branch'; ?>
              </span>
            </div>

            <!-- Detail rows -->
            <div class="px-4 py-3 d-flex flex-column gap-3">
              <div class="d-flex align-items-start gap-3">
                <i class="ri-map-pin-line mt-1" style="color:var(--slate-300);font-size:0.95rem;flex-shrink:0;"></i>
                <span style="font-size:0.83rem;color:var(--slate-600);line-height:1.6;"><?php echo htmlspecialchars($mc['address']); ?></span>
              </div>
              <div class="d-flex align-items-center gap-3">
                <i class="ri-user-line" style="color:var(--slate-300);font-size:0.95rem;flex-shrink:0;"></i>
                <span style="font-size:0.83rem;color:var(--slate-700);font-weight:600;"><?php echo htmlspecialchars($mc['contact']); ?></span>
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <i class="ri-phone-line" style="color:var(--slate-300);font-size:0.95rem;flex-shrink:0;"></i>
                  <a href="tel:<?php echo $mc['phone_link']; ?>" class="text-decoration-none fw-bold"
                    style="font-size:0.9rem;color:<?php echo $mc['color']; ?>;">
                    <?php echo htmlspecialchars($mc['phone']); ?>
                  </a>
                </div>
                <a href="https://wa.me/<?php echo ltrim($mc['phone_link'], '+'); ?>" target="_blank" rel="noopener"
                  class="d-inline-flex align-items-center gap-1 text-decoration-none px-2 py-1 rounded-2 fw-semibold"
                  style="font-size:0.72rem;background:#DCFCE7;color:#16A34A;">
                  <i class="ri-whatsapp-line"></i> WhatsApp
                </a>
              </div>
              <div class="d-flex align-items-center gap-3 pt-1" style="border-top:1px solid var(--slate-100);">
                <i class="ri-time-line" style="color:var(--slate-300);font-size:0.95rem;flex-shrink:0;"></i>
                <span style="font-size:0.78rem;color:var(--slate-500);">Mon – Sat &nbsp;·&nbsp; 9:00 AM – 6:00 PM</span>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Network Grid -->
    <div>
      <div class="d-flex align-items-center gap-3 mb-4">
        <span class="fw-bold" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--slate-400);">Complete Network</span>
        <div style="flex:1;height:1px;background:var(--slate-200);"></div>
        <span style="font-size:0.75rem;color:var(--slate-400);white-space:nowrap;"><?php echo count($network_centers); ?> Centres</span>
      </div>

      <div class="row g-3">
        <?php foreach ($network_centers as $nc):
          $color  = $nc['color']  ?? '#EA580C';
          $is_ho  = ($nc['type'] ?? '') === 'Head Office';
          $phone_link = $nc['phone_link'] ?? ('+91' . preg_replace('/[^0-9]/', '', $nc['phone']));
        ?>
        <div class="col-sm-6 col-lg-3">
          <div class="bg-white rounded-3 p-3 h-100 d-flex flex-column gap-2"
            style="border:1px solid var(--slate-100);border-left:3px solid <?php echo $color; ?>;transition:box-shadow 0.2s,transform 0.2s;"
            onmouseenter="this.style.boxShadow='0 8px 24px rgba(15,23,42,0.09)';this.style.transform='translateY(-2px)'"
            onmouseleave="this.style.boxShadow='none';this.style.transform='none'">

            <!-- City row -->
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="fw-bold mb-0" style="font-size:0.9rem;color:var(--slate-900);"><?php echo htmlspecialchars($nc['city']); ?></h5>
              <?php if ($is_ho): ?>
              <span style="font-size:0.62rem;font-weight:700;background:<?php echo $color; ?>;color:#fff;padding:2px 6px;border-radius:4px;letter-spacing:0.04em;">HO</span>
              <?php endif; ?>
            </div>

            <!-- Coordinator -->
            <div class="d-flex align-items-start gap-2">
              <i class="ri-user-3-line mt-1" style="color:<?php echo $color; ?>;font-size:0.78rem;flex-shrink:0;"></i>
              <div>
                <p class="mb-0" style="font-size:0.78rem;color:var(--slate-700);font-weight:600;line-height:1.3;"><?php echo htmlspecialchars($nc['contact'] ?? $nc['contact_person'] ?? ''); ?></p>
                <p class="mb-0" style="font-size:0.7rem;color:var(--slate-400);"><?php echo htmlspecialchars($nc['designation'] ?? 'Centre Coordinator'); ?></p>
              </div>
            </div>

            <!-- Phone -->
            <div class="d-flex align-items-center gap-2 mt-auto pt-2" style="border-top:1px dashed var(--slate-100);">
              <i class="ri-phone-fill" style="color:<?php echo $color; ?>;font-size:0.78rem;flex-shrink:0;"></i>
              <a href="tel:<?php echo htmlspecialchars($phone_link); ?>" class="text-decoration-none fw-bold"
                style="font-size:0.82rem;color:var(--slate-800);">
                <?php echo htmlspecialchars($nc['phone']); ?>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>
