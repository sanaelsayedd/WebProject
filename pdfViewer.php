<?php
session_start();

$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null;

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_GET['pdf_filename'])) {
    $pdf_filename = $_GET['pdf_filename'];

    // Sanitize the filename to prevent directory traversal
    $pdf_filename = basename($pdf_filename); 

    // Construct the full path to the PDF file (assumes PDFs are in the 'pdf' folder)
    $pdf_path = 'pdf/' . $pdf_filename;

    // Debug: output the full path (this will help you see if the path is correct)
      // This should output something like 'pdf/book1.pdf'
} else {
    echo "Error: No PDF file specified.";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" />
    <title>PDF Viewer</title>
    <link rel="stylesheet" href="css/pdfViewerStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Ensure pdf.js is loaded first -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script> 

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Replace this with the correct PHP dynamic value if needed
        const pdf_filename = '<?php echo isset($pdf_filename) ? $pdf_filename : ''; ?>';
        if (!pdf_filename) {
          alert('Error: No PDF file specified.');
          return;
        }

        const url = 'pdf/' + pdf_filename;

        
        let pdfDoc = null,
          pageNum = 1,
          pageIsRendering = false,
          pageNumIsPending = null;

        const scale = 1.5,
          canvas = document.querySelector('#pdf-render'),
          ctx = canvas.getContext('2d');

        // Render page function
        const renderPage = num => {
          pageIsRendering = true;
          console.log("Rendering page: " + num);

          pdfDoc.getPage(num).then(page => {
            const viewport = page.getViewport({ scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderCtx = {
              canvasContext: ctx,
              viewport
            };

            page.render(renderCtx).promise.then(() => {
              pageIsRendering = false;

             
              if (pageNumIsPending !== null) {
                renderPage(pageNumIsPending);
                pageNumIsPending = null;
              }
            });

            // Update the current page number display
            document.querySelector('#page-num').textContent = num;
          }).catch(err => {
            console.error("Error rendering page: " + err.message);
          });
        };

        // Queue page rendering
        const queueRenderPage = num => {
          if (pageIsRendering) {
            pageNumIsPending = num;
          } else {
            renderPage(num);
          }
        };

        // Show the previous page
        const showPrevPage = () => {
          if (pageNum <= 1) return;
          pageNum--;
          queueRenderPage(pageNum);
        };

        // Show the next page
        const showNextPage = () => {
          if (pageNum >= pdfDoc.numPages) return;
          pageNum++;
          queueRenderPage(pageNum);
        };

        // Fetch the PDF document and render the first page
        pdfjsLib.getDocument(url).promise.then(pdfDoc_ => {
          pdfDoc = pdfDoc_;
          document.querySelector('#page-count').textContent = pdfDoc.numPages;
          renderPage(pageNum);
        }).catch(err => {
          console.error("Error loading PDF: " + err.message);
          const div = document.createElement('div');
          div.className = 'error';
          div.appendChild(document.createTextNode(err.message));
          document.querySelector('body').insertBefore(div, canvas);
        });

        // Event listeners for page navigation
        document.querySelector('#prev-page').addEventListener('click', showPrevPage);
        document.querySelector('#next-page').addEventListener('click', showNextPage);
      });
    </script>
  </head>

  <body>
  <header class="header">
    <div class="logo">
        <a href="index.php">Knowledge Nest</a>
    </div>
    <nav class="nav-bar">
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="books.php">Books</a></li>
            <?php if ($is_logged_in): ?>
                <?php if ($userType === 'user'): ?>
                    <li><a href="myAccount.php">My Account</a></li>
                <?php elseif ($userType === 'admin'): ?>
                    <li><a href="dashboard.php">Admin Dashboard</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="login">
        <?php if ($is_logged_in): ?>
            <form method="GET" action="index.php">
            <button type="submit" name="logout">
                <i class="fa-solid fa-sign-out-alt"></i><b class="logout-text">Logout</b>
            </button>
            </form>
        <?php else: ?>
            <a href="login.php" class="login-icon">
                <button><i class="fa-solid fa-user"></i><b class="login-text">Login</b></button>
            </a>
        <?php endif; ?>
    </div>
</header>
<div class="top-bar">
  <button class="btn" id="prev-page">
    <i class="fas fa-arrow-circle-left"></i> Prev Page
  </button>
  <span class="page-info">
    Page <span id="page-num"></span> of <span id="page-count"></span>
  </span>
  <button class="btn" id="next-page">
    Next Page <i class="fas fa-arrow-circle-right"></i>
  </button>
</div>

<!-- Canvas to render the PDF -->
<canvas id="pdf-render"></canvas>

<footer>
    <div class="footer-container">
        <!-- Logo Section -->
        <div class="footer-logo-section">
            <img src="css/Image/KnowledgeNest-noBK.png" alt="Harvard Shield" class="footer-logo">
        </div>

        <!-- Links and License Section -->
        <div class="footer-content">
            <div class="footer-links">
                <!-- First Column -->
                <div class="link-column">
                    <p>GIVING TO THE LIBRARY</p>
                    <p>OFFICE OF THE PROVOST</p>
                    <p>HOLLIS</p>
                    <p>HOLLIS FOR ARCHIVAL DISCOVERY</p>
                    <p>DATABASES</p>
                </div>

                <!-- Second Column -->
                <div class="link-column">
                    <p>NEWSLETTERS/SOCIAL</p>
                    <p>STAFF PORTAL</p>
                    <p>LIBRARY ACCESSIBILITY</p>
                    <p>REPORT A PROBLEM</p>
                </div>

                <!-- Third Column -->
                <div class="link-column">
                    <div class="footer-policy-links">
                        <a href="#">Accessibility</a>
                        <a href="#">Privacy</a>
                    </div>
                </div>
            </div>

            <!-- License Section -->
            <p class="footer-license">
                Creative Commons Attribution 4.0 International License. Except where otherwise noted, 
                this work is subject to a <a href="#">Creative Commons Attribution 4.0 International License</a> 
                which allows anyone to share and adapt our material as long as proper attribution is given. 
                For details and exceptions, see the <a href="#">Harvard Library Copyright Policy</a> 
                &copy;2024 Presidents and Fellows of Harvard College.
            </p>
        </div>
    </div>
</footer>
  </body>


</html>
