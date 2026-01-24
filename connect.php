<?php

    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        dob DATE NULL,
        education VARCHAR(100) NULL,
        major VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);

    echo "✅ Table 'users' created successfully!";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}

?>
