<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FAQ & Newsletter</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      margin: 0;
    }

    .top-banner {
      background-color: #6c63ff;
      color: white;
      text-align: center;
      font-size: 0.9rem;
      padding: 5px 0;
    }

    .faq-section {
      padding: 60px 20px;
      text-align: center;
      background-color: #f2efff;
    }

    .faq-section h2 {
      font-size: 2rem;
      margin-bottom: 40px;
    }

    .accordion {
      max-width: 700px;
      margin: auto;
      text-align: left;
    }

    .accordion-item {
      background: #f6f4ff;
      border: 1px solid #ddd;
      margin-bottom: 10px;
      border-radius: 5px;
      overflow: hidden;
    }

    .accordion-header {
      padding: 15px 20px;
      cursor: pointer;
      font-weight: 600;
    }

    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s ease, padding 0.3s ease;
      padding: 0 20px;
    }

    .accordion-content.open {
      padding: 15px 20px;
      max-height: 200px;
    }

    .newsletter {
      padding: 60px 20px;
      background: linear-gradient(to right, #e7ccff, #ffd9cc);
      text-align: center;
    }

    .newsletter h3 {
      font-size: 1.5rem;
      margin-bottom: 20px;
    }

    .newsletter form {
      max-width: 400px;
      margin: auto;
    }

    .newsletter input[type="email"] {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 4px;
      margin-bottom: 10px;
    }

    .newsletter button {
      padding: 12px 25px;
      background: #1e1e2f;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 600;
    }

    .powered-by {
      margin-top: 10px;
      font-size: 0.85rem;
      color: #555;
    }
  </style>
</head>
<body>

  <!-- FAQ Section -->
  <section class="faq-section">
    <h2>Frequently Asked Questions</h2>
    <div class="accordion">
      <div class="accordion-item">
        <div class="accordion-header">Where do you deliver?</div>
        <div class="accordion-content">We currently deliver Nationwide</div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">Is My Package Sent Safely?</div>
        <div class="accordion-content">Yes, all packages are securely packed and insured.</div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">Do you offer any refunds or exchanges?</div>
        <div class="accordion-content">We do offer exchanges for defective products within 7 days.</div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">Can I cancel my order?</div>
        <div class="accordion-content">Yes, cancellation is possible within 24 hours of ordering.</div>
      </div>
    </div>
  </section>

  <!-- Newsletter Section -->
  <section class="newsletter">
    <h3>Subscribe to Our Newsletter</h3>
    <form method="post" action="">
      <input type="email" name="email" placeholder="Email Address" required />
      <button type="submit">Subscribe</button>
    </form>
    <div class="powered-by">made by <strong>Lakshya</strong></div>
  </section>

  <script>
    const headers = document.querySelectorAll('.accordion-header');
    headers.forEach(header => {
      header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        content.classList.toggle('open');
      });
    });
  </script>
</body>
</html>
