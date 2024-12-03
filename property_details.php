<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
require_once 'auth_middleware.php';

try {
   $pdo = connectDB();
   
   if (!isset($_GET['id'])) {
       header("Location: buyer_dashboard.php");
       exit();
   }
   
   $propertyId = $_GET['id'];
   $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
   $stmt->execute([$propertyId]);
   $property = $stmt->fetch(PDO::FETCH_ASSOC);
   
   if (!$property) {
       header("Location: buyer_dashboard.php");
       exit();
   }
   
   // Check if property is in user's wishlist
   $wishlistStmt = $pdo->prepare("SELECT id FROM wishlists WHERE user_id = ? AND property_id = ?");
   $wishlistStmt->execute([$_SESSION['user_id'], $propertyId]);
   $isWishlisted = $wishlistStmt->rowCount() > 0;

} catch(PDOException $e) {
   die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo htmlspecialchars($property['title']); ?> - Property Details</title>
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

       .container {
           max-width: 1200px;
           margin: 2rem auto;
           padding: 0 1rem;
       }

       .property-details {
           background: white;
           border-radius: 8px;
           box-shadow: 0 2px 4px rgba(0,0,0,0.1);
           overflow: hidden;
       }

       .property-image {
           width: 100%;
           height: 400px;
           object-fit: cover;
       }

       .property-info {
           padding: 2rem;
       }

       .property-price {
           font-size: 2rem;
           font-weight: bold;
           color: #2563eb;
           margin-bottom: 1rem;
       }

       .property-title {
           font-size: 1.5rem;
           margin-bottom: 1rem;
       }

       .property-location {
           color: #64748b;
           margin-bottom: 1rem;
       }

       .property-stats {
           display: grid;
           grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
           gap: 1rem;
           margin: 1rem 0;
           padding: 1rem 0;
           border-top: 1px solid #e2e8f0;
           border-bottom: 1px solid #e2e8f0;
       }

       .stat-item {
           display: flex;
           align-items: center;
           gap: 0.5rem;
       }

       .stat-item i {
           color: #2563eb;
       }

       .description {
           margin: 1rem 0;
           line-height: 1.6;
       }

       .actions {
           display: flex;
           gap: 1rem;
           margin-top: 2rem;
       }

       .btn {
           padding: 0.75rem 1.5rem;
           border-radius: 4px;
           text-decoration: none;
           font-weight: 500;
           cursor: pointer;
       }

       .btn-primary {
           background: #2563eb;
           color: white;
           border: none;
       }

       .btn-outline {
           border: 2px solid #2563eb;
           color: #2563eb;
       }

       .back-link {
           display: inline-block;
           margin-bottom: 1rem;
           color: #2563eb;
           text-decoration: none;
       }
   </style>
</head>
<body>
   <div class="container">
       <a href="buyer_dashboard.php" class="back-link">
           <i class="fas fa-arrow-left"></i> Back to Dashboard
       </a>
       
       <div class="property-details">
           <img src="<?php echo htmlspecialchars($property['image_url']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="property-image">
           
           <div class="property-info">
               <div class="property-price">$<?php echo number_format($property['price']); ?></div>
               <h1 class="property-title"><?php echo htmlspecialchars($property['title']); ?></h1>
               <div class="property-location">
                   <i class="fas fa-map-marker-alt"></i>
                   <?php echo htmlspecialchars($property['location']); ?>
               </div>
               
               <div class="property-stats">
                   <div class="stat-item">
                       <i class="fas fa-bed"></i>
                       <span><?php echo $property['bedrooms']; ?> Bedrooms</span>
                   </div>
                   <div class="stat-item">
                       <i class="fas fa-bath"></i>
                       <span><?php echo $property['bathrooms']; ?> Bathrooms</span>
                   </div>
                   <div class="stat-item">
                       <i class="fas fa-ruler-combined"></i>
                       <span><?php echo number_format($property['square_feet']); ?> sq ft</span>
                   </div>
                   <div class="stat-item">
                       <i class="fas fa-home"></i>
                       <span><?php echo ucfirst($property['property_type']); ?></span>
                   </div>
               </div>
               
               <div class="description">
                   <?php echo nl2br(htmlspecialchars($property['description'])); ?>
               </div>
               
               <div class="actions">
                   <button onclick="toggleWishlist(<?php echo $property['id']; ?>)" class="btn btn-outline" id="wishlistBtn">
                       <i class="fas fa-heart"></i>
                       <?php echo $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>
                   </button>
                   <a href="mailto:contact@propertyconnect.com?subject=Inquiry about <?php echo urlencode($property['title']); ?>" class="btn btn-primary">
                       Contact Agent
                   </a>
               </div>
           </div>
       </div>
   </div>

   <script>
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
               const btn = document.getElementById('wishlistBtn');
               if (btn.textContent.includes('Add')) {
                   btn.innerHTML = '<i class="fas fa-heart"></i> Remove from Wishlist';
               } else {
                   btn.innerHTML = '<i class="fas fa-heart"></i> Add to Wishlist';
               }
           }
       });
   }
   </script>
</body>
</html>