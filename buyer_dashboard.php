<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
require_once 'auth_middleware.php';
checkAuth();
checkUserType(['buyer']);

// Database connection
$pdo = connectDB();

// Check if first time login
$stmt = $pdo->prepare("SELECT last_login FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$firstTimeLogin = $user['last_login'] === null;

// Get wishlist items
$wishlistStmt = $pdo->prepare("
    SELECT p.* FROM properties p
    JOIN wishlists w ON p.id = w.property_id
    WHERE w.user_id = ?
");
$wishlistStmt->execute([$_SESSION['user_id']]);
$wishlistItems = $wishlistStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard - Property Connect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f8fafc;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: <?php echo $firstTimeLogin ? 'block' : 'none'; ?>;
        }

        .search-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .search-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-select {
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .property-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .property-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .property-card:hover {
            transform: translateY(-5px);
        }

        .property-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .property-info {
            padding: 1rem;
        }

        .property-price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }

        .property-details {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.5rem;
            color: #64748b;
        }

        .property-location {
            font-size: 0.875rem;
            color: #64748b;
        }

        .card-actions {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .wishlist-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .tab {
            padding: 0.5rem 1rem;
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            border-bottom-color: #2563eb;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php if($firstTimeLogin): ?>
        <div class="welcome-banner">
            <h2>Welcome to Property Connect! 🎉</h2>
            <p>Thank you for choosing us as your property search partner. Let's find your dream property together!</p>
        </div>
        <?php endif; ?>

        <div class="search-section">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search by location, property type, or keyword...">
            </div>
            <div class="filters">
                <select class="filter-select" id="propertyType">
                    <option value="">Property Type</option>
                    <option value="house">House</option>
                    <option value="apartment">Apartment</option>
                    <option value="condo">Condo</option>
                </select>
                <select class="filter-select" id="priceRange">
                    <option value="">Price Range</option>
                    <option value="0-200000">$0 - $200,000</option>
                    <option value="200000-500000">$200,000 - $500,000</option>
                    <option value="500000+">$500,000+</option>
                </select>
                <select class="filter-select" id="bedrooms">
                    <option value="">Bedrooms</option>
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="3">3+</option>
                    <option value="4">4+</option>
                </select>
            </div>
        </div>

        <div class="tabs">
            <button class="tab active" onclick="showTab('all')">All Properties</button>
            <button class="tab" onclick="showTab('wishlist')">My Wishlist</button>
        </div>

        <div class="property-grid" id="propertyGrid">
            <!-- Property cards will be loaded here -->
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        loadProperties('all');

        // Search and filter functionality
        const searchInput = document.querySelector('.search-input');
        const filterSelects = document.querySelectorAll('.filter-select');

        searchInput.addEventListener('input', debounce(function() {
            loadProperties(currentTab);
        }, 500));

        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                loadProperties(currentTab);
            });
        });
    });

    let currentTab = 'all';

    function showTab(tab) {
        currentTab = tab;
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        event.target.classList.add('active');
        loadProperties(tab);
    }

    function loadProperties(tab) {
        const searchTerm = document.querySelector('.search-input').value;
        const propertyType = document.getElementById('propertyType').value;
        const priceRange = document.getElementById('priceRange').value;
        const bedrooms = document.getElementById('bedrooms').value;

        fetch(`get_properties.php?tab=${tab}&search=${searchTerm}&type=${propertyType}&price=${priceRange}&beds=${bedrooms}`)
            .then(response => response.json())
            .then(data => {
                const grid = document.getElementById('propertyGrid');
                grid.innerHTML = data.map(property => createPropertyCard(property)).join('');
            });
    }

    function createPropertyCard(property) {
        return `
            <div class="property-card">
                <img src="${property.image_url}" class="property-image" alt="${property.title}">
                <div class="property-info">
                    <div class="property-price">$${property.price.toLocaleString()}</div>
                    <div class="property-details">
                        <span>${property.bedrooms} beds</span>
                        <span>${property.bathrooms} baths</span>
                        <span>${property.square_feet} sq ft</span>
                    </div>
                    <div class="property-location">
                        <i class="fas fa-map-marker-alt"></i>
                        ${property.location}
                    </div>
                </div>
                <div class="card-actions">
                    <button class="wishlist-btn" onclick="toggleWishlist(${property.id})">
                        <i class="fas fa-heart"></i>
                        Add to Wishlist
                    </button>
                    <a href="property_details.php?id=${property.id}" class="btn">View Details</a>
                </div>
            </div>
        `;
    }

    function toggleWishlist(propertyId) {
        fetch('toggle_wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                property_id: propertyId
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Update wishlist icon
                loadProperties(currentTab);
            }
        });
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
    </script>
</body>
</html>