<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once 'db.php';

try {
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $products = [];
    while($row = $result->fetch_assoc()) {
        // Sanitize data
        $row = array_map('htmlspecialchars', $row);
        $products[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => $products
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
} finally {
    $conn->close();
}
?>