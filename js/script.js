const togglebtn = document.querySelector(".toggle-btn");
const togglebtnIcon = document.querySelector(".toggle-btn i");
const dropDownMenu = document.querySelector(".dropdown-menu");

togglebtn.onclick = function () {
    dropDownMenu.classList.toggle("open");
    const isOpen  = dropDownMenu.classList.contains("open")
    togglebtnIcon.classList = isOpen
    ? "fa-solid fa-xmark"
    : "fa-solid fa-bars"
}
// ==================================================================
// Add an event listener to the search button or form submission
document.getElementById("search-button").addEventListener("click", function(e) {
    e.preventDefault(); // Prevent default form submission

    var searchInput = document.getElementById("search-input").value.trim(); // Get the value from the input field

    if (searchInput) {
        // Redirect to books.php with the search query as a URL parameter
        window.location.href = "books.php?search=" + encodeURIComponent(searchInput);
    } else {
        // If no input, just reload the page without the query string
        window.location.href = "books.php";
    }
});


// ===========================================================
function fetchBooks(searchQuery = '') {
    // Make sure searchQuery is properly encoded
    const url = `booksApi.php?search=${encodeURIComponent(searchQuery)}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const bookList = document.getElementById('bookList');
            bookList.innerHTML = ''; // Clear the book list before adding new books

            if (data.length > 0) {
                data.forEach(book => {
                    const bookHtml = `
                        <div class="book-card">
                            <img src="css/Image/Book2.jpg" alt="Book Cover" class="book-image">
                            <div class="book-details">
                                <h2 class="book-title">${book.Title}</h2>
                                <p class="book-author"><strong>Author:</strong> ${book.Author}</p>
                                <p class="book-status"><strong>Status:</strong> ${book.Status}</p>
                                <p class="book-edition"><strong>Edition:</strong> ${book.Edition}</p>
                                <p class="book-price"><strong>Price:</strong> $${book.Price}</p>
                                <p class="book-quantity"><strong>Quantity:</strong> ${book.Quantity}</p>
                                <p class="book-category"><strong>Category:</strong> ${book.Category}</p>
                            </div>
                        </div>
                    `;
                    bookList.innerHTML += bookHtml; // Append each book to the list
                });
            } else {
                bookList.innerHTML = '<p>No books found!</p>'; // Show message if no books found
            }
        })
        .catch(error => {
            const bookList = document.getElementById('bookList');
            bookList.innerHTML = '<p>Error fetching data. Please try again.</p>'; // Error message on failure
        });
}

// Initial fetch to display all books when the page loads
document.addEventListener('DOMContentLoaded', function() {
    fetchBooks(); // Fetch all books by default when the page loads
});

// Handle the search functionality using plain JavaScript
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form submission
    const searchQuery = document.getElementById('search').value.trim(); // Get the search query
    if (searchQuery) {
        fetchBooks(searchQuery); // Fetch books based on search query
    } else {
        fetchBooks(); // Fetch all books if search is empty
    }
});
// ===========================================================
// Featured Books
// Get scroll container and buttons
const container = document.querySelector('.books-container');
const leftBtn = document.querySelector('.scroll-btn.left');
const rightBtn = document.querySelector('.scroll-btn.right');

// Calculate scroll amount based on book card width + gap
const scrollAmount = 320; // 300px card width + 20px gap

// Add click handlers for scroll buttons
leftBtn.addEventListener('click', () => {
    container.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
    });
});

rightBtn.addEventListener('click', () => {
    container.scrollBy({
        left: scrollAmount, 
        behavior: 'smooth'
    });
});

// Show/hide scroll buttons based on scroll position
const toggleScrollButtons = () => {
    leftBtn.style.display = container.scrollLeft > 0 ? 'flex' : 'none';
    rightBtn.style.display = 
        container.scrollLeft < container.scrollWidth - container.clientWidth ? 'flex' : 'none';
};

container.addEventListener('scroll', toggleScrollButtons);
window.addEventListener('resize', toggleScrollButtons);

// Initial button visibility
toggleScrollButtons();
// ===========================================================