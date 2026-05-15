/* ============================================
   GLOBAL STATE MANAGEMENT
   ============================================ */
let currentUser = null;
let currentPage = 'home';
let sidebarOpen = false;

/* ============================================
   INITIALIZATION
   ============================================ */
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    handleResponsive();
    
    // Check if user is logged in (from localStorage)
    const savedUser = localStorage.getItem('bookifyUser');
    if (savedUser) {
        currentUser = JSON.parse(savedUser);
        showDashboard();
    }
});

/* ============================================
   EVENT LISTENERS SETUP
   ============================================ */
function initializeEventListeners() {
    // Navigation items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', handleNavigation);
    });

    // Auth forms
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }

    // Business type selector (register)
    document.querySelectorAll('.business-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            selectBusinessType(btn);
        });
    });

    // Transaction type selector
    document.querySelectorAll('.type-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            selectTransactionType(btn);
        });
    });

    // Category selector
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            selectCategory(btn);
        });
    });

    // Modal overlays (close on click)
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function() {
            this.closest('.modal').classList.remove('active');
        });
    });

    // Transaction form
    const txForm = document.getElementById('transactionForm');
    if (txForm) {
        txForm.addEventListener('submit', handleTransactionSubmit);
    }

    // Add product form
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
        addProductForm.addEventListener('submit', handleAddProduct);
    }

    // Edit product form
    const editProductForm = document.getElementById('editProductForm');
    if (editProductForm) {
        editProductForm.addEventListener('submit', handleEditProduct);
    }

    // Menu toggle
    const menuToggle = document.getElementById('menuToggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleSidebar);
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', function(e) {
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.getElementById('menuToggle');
        if (sidebarOpen && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            closeSidebar();
        }
    });
}

/* ============================================
   AUTHENTICATION HANDLERS
   ============================================ */
function handleLogin(e) {
    e.preventDefault();
    
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    // Simple validation
    if (!email || !password) {
        alert('Silakan isi semua field');
        return;
    }
    
    // Simulate login (in production, this would be an API call)
    const user = {
        id: 1,
        email: email,
        name: email.split('@')[0],
        loginTime: new Date()
    };
    
    localStorage.setItem('bookifyUser', JSON.stringify(user));
    currentUser = user;
    
    showDashboard();
}

function handleRegister(e) {
    e.preventDefault();
    
    const fullname = document.getElementById('regFullName').value;
    const businessType = document.getElementById('regBusinessType').value;
    const email = document.getElementById('regEmail').value;
    const password = document.getElementById('regPassword').value;
    
    if (!fullname || !email || !password) {
        alert('Silakan isi semua field');
        return;
    }
    
    // Simulate registration
    const user = {
        id: 1,
        email: email,
        name: fullname,
        businessType: businessType,
        registrationTime: new Date()
    };
    
    localStorage.setItem('bookifyUser', JSON.stringify(user));
    currentUser = user;
    
    showDashboard();
}

function switchToRegister() {
    document.getElementById('loginPage').classList.remove('active');
    document.getElementById('registerPage').classList.add('active');
    document.body.classList.add('auth-open');
}

function switchToLogin() {
    document.getElementById('registerPage').classList.remove('active');
    document.getElementById('loginPage').classList.add('active');
    document.body.classList.add('auth-open');
}

function goToLogin() {
    const landingPage = document.getElementById('landingPage');
    const loginPage = document.getElementById('loginPage');
    
    // Add exit animation to landing page
    landingPage.classList.add('exit');
    
    // Wait for animation, then show login page
    setTimeout(() => {
        landingPage.classList.remove('active');
        landingPage.classList.remove('exit');
        loginPage.classList.add('active');
        loginPage.classList.add('enter');
        document.body.classList.add('auth-open');
        
        // Remove enter class after animation completes
        setTimeout(() => {
            loginPage.classList.remove('enter');
        }, 500);
    }, 500);
}

function goBackToLanding() {
    const landingPage = document.getElementById('landingPage');
    const loginPage = document.getElementById('loginPage');
    
    // Add exit animation to login page
    loginPage.classList.add('exit');
    
    // Wait for animation, then show landing page
    setTimeout(() => {
        loginPage.classList.remove('active');
        loginPage.classList.remove('exit');
        landingPage.classList.add('active');
        landingPage.classList.add('enter');
        document.body.classList.remove('auth-open');
        
        // Remove enter class after animation completes
        setTimeout(() => {
            landingPage.classList.remove('enter');
        }, 500);
    }, 500);
}

/* ============================================
   AUTHENTICATION STATE
   ============================================ */
function showDashboard() {
    document.getElementById('landingPage').classList.remove('active');
    document.getElementById('loginPage').classList.remove('active');
    document.getElementById('registerPage').classList.remove('active');
    document.getElementById('dashboardPage').classList.add('active');
    document.body.classList.remove('auth-open');
    
    // Update profile name
    if (currentUser) {
        document.querySelector('.profile-name').textContent = currentUser.name;
    }
    
    switchPage('home');
}

function logout() {
    if (confirm('Yakin ingin logout?')) {
        localStorage.removeItem('bookifyUser');
        currentUser = null;
        document.getElementById('dashboardPage').classList.remove('active');
        document.getElementById('loginPage').classList.add('active');
        document.getElementById('loginForm').reset();
        document.body.classList.add('auth-open');
    }
}

/* ============================================
   NAVIGATION HANDLERS
   ============================================ */
function handleNavigation(e) {
    const pageType = this.dataset.page;
    if (pageType) {
        switchPage(pageType);
    }
    closeSidebar();
}

function switchPage(pageName) {
    // Update active nav item
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
        if (item.dataset.page === pageName) {
            item.classList.add('active');
        }
    });
    
    // Hide all content
    document.querySelectorAll('.page-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Show selected content
    const pageId = pageName + 'Content';
    const pageElement = document.getElementById(pageId);
    if (pageElement) {
        pageElement.classList.add('active');
    }
    
    // Update page title
    const titles = {
        'home': 'Home',
        'transactions': 'Catat Transaksi',
        'stock': 'Kelola Stok',
        'reports': 'Laporan Keuangan',
        'insights': 'Insight AI'
    };
    
    document.getElementById('pageTitle').textContent = titles[pageName] || 'Home';
    currentPage = pageName;
}

/* ============================================
   FORM SELECTORS
   ============================================ */
function selectBusinessType(btn) {
    // Remove active class from all buttons
    document.querySelectorAll('.business-btn').forEach(b => {
        b.classList.remove('active');
    });
    
    // Add active class to clicked button
    btn.classList.add('active');
    
    // Update hidden input
    document.getElementById('regBusinessType').value = btn.dataset.type;
}

function selectTransactionType(btn) {
    // Remove active class from all buttons
    document.querySelectorAll('.type-btn').forEach(b => {
        b.classList.remove('active');
    });
    
    // Add active class to clicked button
    btn.classList.add('active');
    
    // Update hidden input
    document.getElementById('txType').value = btn.dataset.type;
}

function selectCategory(btn) {
    // Remove active class from all buttons
    document.querySelectorAll('.category-btn').forEach(b => {
        b.classList.remove('active');
    });
    
    // Add active class to clicked button
    btn.classList.add('active');
}

/* ============================================
   MODAL HANDLERS - TRANSACTIONS
   ============================================ */
function openTransactionModal() {
    document.getElementById('transactionModal').classList.add('active');
    // Reset form
    document.getElementById('transactionForm').reset();
    // Reset transaction type
    document.querySelectorAll('.type-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector('.type-btn[data-type="income"]').classList.add('active');
    document.getElementById('txType').value = 'income';
}

function closeTransactionModal() {
    document.getElementById('transactionModal').classList.remove('active');
}

function handleTransactionSubmit(e) {
    e.preventDefault();
    
    const txType = document.getElementById('txType').value;
    const txItem = document.getElementById('txItem').value;
    const txAmount = document.getElementById('txAmount').value;
    const txNotes = document.getElementById('txNotes').value;
    
    if (!txItem || !txAmount) {
        alert('Silakan isi field yang diperlukan');
        return;
    }
    
    // Simulate saving transaction
    console.log('Transaction saved:', {
        type: txType,
        item: txItem,
        amount: txAmount,
        notes: txNotes,
        date: new Date()
    });
    
    alert('Transaksi berhasil disimpan!');
    closeTransactionModal();
}

/* ============================================
   MODAL HANDLERS - PRODUCTS
   ============================================ */
function openAddProductModal() {
    document.getElementById('addProductModal').classList.add('active');
    document.getElementById('addProductForm').reset();
}

function closeAddProductModal() {
    document.getElementById('addProductModal').classList.remove('active');
}

function openEditProductModal(productId) {
    document.getElementById('editProductModal').classList.add('active');
    
    // Simulate loading product data
    const productData = {
        1: { name: 'Product A', stock: 25, min: 10, price: 50000 },
        2: { name: 'Product B', stock: 8, min: 15, price: 75000 },
        3: { name: 'Raw Material X', stock: 100, min: 30, price: 5000 },
        4: { name: 'Packaging', stock: 5, min: 20, price: 2000 }
    };
    
    const product = productData[productId];
    if (product) {
        document.getElementById('editProductName').value = product.name;
        document.getElementById('editProductStock').value = product.stock;
        document.getElementById('editProductMin').value = product.min;
        document.getElementById('editProductPrice').value = product.price;
    }
    
    document.getElementById('editProductModal').dataset.productId = productId;
}

function closeEditProductModal() {
    document.getElementById('editProductModal').classList.remove('active');
}

function handleAddProduct(e) {
    e.preventDefault();
    
    const name = document.getElementById('productName').value;
    const stock = document.getElementById('productStock').value;
    const min = document.getElementById('productMin').value;
    const price = document.getElementById('productPrice').value;
    
    if (!name || !stock || !min || !price) {
        alert('Silakan isi semua field');
        return;
    }
    
    console.log('Product added:', { name, stock, min, price });
    alert('Produk berhasil ditambahkan!');
    closeAddProductModal();
}

function handleEditProduct(e) {
    e.preventDefault();
    
    const name = document.getElementById('editProductName').value;
    const stock = document.getElementById('editProductStock').value;
    const min = document.getElementById('editProductMin').value;
    const price = document.getElementById('editProductPrice').value;
    
    if (!name || !stock || !min || !price) {
        alert('Silakan isi semua field');
        return;
    }
    
    console.log('Product updated:', { name, stock, min, price });
    alert('Produk berhasil diperbarui!');
    closeEditProductModal();
}

/* ============================================
   RESPONSIVE SIDEBAR HANDLERS
   ============================================ */
function toggleSidebar() {
    if (sidebarOpen) {
        closeSidebar();
    } else {
        openSidebar();
    }
}

function openSidebar() {
    document.querySelector('.sidebar').classList.add('active');
    sidebarOpen = true;
}

function closeSidebar() {
    document.querySelector('.sidebar').classList.remove('active');
    sidebarOpen = false;
}

/* ============================================
   RESPONSIVE UTILITIES
   ============================================ */
function handleResponsive() {
    window.addEventListener('resize', debounce(function() {
        // Close sidebar if window is large
        if (window.innerWidth > 1024) {
            closeSidebar();
        }
    }, 250));
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/* ============================================
   UTILITY FUNCTIONS
   ============================================ */
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(date));
}

/* ============================================
   KEYBOARD SHORTCUTS
   ============================================ */
document.addEventListener('keydown', function(e) {
    // Close modals with Escape key
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }
    
    // Toggle sidebar with Ctrl+Shift+S
    if (e.ctrlKey && e.shiftKey && e.key === 'S') {
        e.preventDefault();
        toggleSidebar();
    }
});

/* ============================================
   SIMULATED DATA & HELPERS
   ============================================ */
const mockTransactions = [
    {
        id: 1,
        type: 'income',
        item: 'Penjualan Produk',
        amount: 500000,
        date: new Date(2023, 6, 1),
        icon: '💰'
    },
    {
        id: 2,
        type: 'expense',
        item: 'Pembelian Bahan Baku',
        amount: 200000,
        date: new Date(2023, 6, 2),
        icon: '🛍️'
    },
    {
        id: 3,
        type: 'expense',
        item: 'Biaya Operasional',
        amount: 150000,
        date: new Date(2023, 6, 3),
        icon: '💼'
    }
];

const mockProducts = [
    {
        id: 1,
        name: 'Product A',
        stock: 25,
        min: 10,
        price: 50000,
        status: 'ready'
    },
    {
        id: 2,
        name: 'Product B',
        stock: 8,
        min: 15,
        price: 75000,
        status: 'warning'
    },
    {
        id: 3,
        name: 'Raw Material X',
        stock: 100,
        min: 30,
        price: 5000,
        status: 'ready'
    },
    {
        id: 4,
        name: 'Packaging',
        stock: 5,
        min: 20,
        price: 2000,
        status: 'warning'
    }
];

/* ============================================
   LOCAL STORAGE HELPERS
   ============================================ */
function saveToLocalStorage(key, data) {
    try {
        localStorage.setItem(key, JSON.stringify(data));
        return true;
    } catch (e) {
        console.error('Error saving to localStorage:', e);
        return false;
    }
}

function getFromLocalStorage(key) {
    try {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    } catch (e) {
        console.error('Error reading from localStorage:', e);
        return null;
    }
}

function removeFromLocalStorage(key) {
    try {
        localStorage.removeItem(key);
        return true;
    } catch (e) {
        console.error('Error removing from localStorage:', e);
        return false;
    }
}

/* ============================================
   TRANSACTION CATEGORIES DATA
   ============================================ */
const transactionCategories = {
    income: ['Penjualan', 'Investasi', 'Pinjaman', 'Lainnya'],
    expense: ['Bahan Baku', 'Gaji', 'Sewa', 'Utilitas', 'Pemasaran', 'Lainnya']
};

/* ============================================
   TRANSACTION TYPE HANDLER
   ============================================ */
function updateTransactionCategories() {
    const txType = document.getElementById('txType').value;
    const categories = transactionCategories[txType] || [];
    const categorySelector = document.getElementById('categorySelector');
    
    categorySelector.innerHTML = '';
    
    categories.forEach(category => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'category-btn';
        btn.setAttribute('data-category', category.toLowerCase());
        btn.textContent = category;
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            selectCategory(btn);
        });
        categorySelector.appendChild(btn);
    });
    
    // Select first category by default
    if (categorySelector.children.length > 0) {
        categorySelector.children[0].classList.add('active');
    }
}

// Update categories when transaction type changes
document.addEventListener('DOMContentLoaded', function() {
    const typeButtons = document.querySelectorAll('.type-btn');
    typeButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active from all
            typeButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update hidden input
            document.getElementById('txType').value = this.dataset.type;
            
            // Update categories
            updateTransactionCategories();
        });
    });
    
    // Initial load
    updateTransactionCategories();
});

/* ============================================
   COMMUNITY HANDLERS
   ============================================ */
function openCommunityForm() {
    document.getElementById('communityModal').classList.add('active');
    document.getElementById('communityForm').reset();
    
    // Reset category selection
    document.querySelectorAll('#communityModal .category-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector('#communityModal .category-btn[data-category="success"]').classList.add('active');
    document.getElementById('communityCategory').value = 'success';
}

function closeCommunityForm() {
    document.getElementById('communityModal').classList.remove('active');
}

function handleCommunityFormSubmit(e) {
    e.preventDefault();
    
    const title = document.getElementById('communityTitle').value;
    const content = document.getElementById('communityContent').value;
    const category = document.getElementById('communityCategory').value;
    
    if (!title || !content) {
        alert('Silakan isi semua field');
        return;
    }
    
    if (content.length < 100) {
        alert('Cerita minimal 100 karakter');
        return;
    }
    
    console.log('Community post submitted:', { title, content, category });
    alert('Cerita Anda berhasil dibagikan!');
    closeCommunityForm();
}

// Setup community form submit
document.addEventListener('DOMContentLoaded', function() {
    const communityForm = document.getElementById('communityForm');
    if (communityForm) {
        communityForm.addEventListener('submit', handleCommunityFormSubmit);
    }
    
    // Community category selection
    const communityCategories = document.querySelectorAll('#communityModal .category-btn');
    communityCategories.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            communityCategories.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('communityCategory').value = btn.dataset.category;
        });
    });
});

/* ============================================
   COMMUNITY TAB FILTERING
   ============================================ */
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active from all tabs
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // In production, this would filter posts
            console.log('Filtering by tab:', btn.dataset.tab);
        });
    });
});

/* ============================================
   DOWNLOAD REPORT
   ============================================ */
function downloadReport() {
    alert('Fitur download laporan PDF akan segera tersedia!');
    // In production, this would generate and download a PDF
    console.log('Download report clicked');
}

