CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price NUMERIC(10, 2) NOT NULL CHECK (price >= 0),
    sku VARCHAR(50) UNIQUE NOT NULL, -- Stock Keeping Unit, identificador único
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Opcional: Función y trigger para actualizar automáticamente la columna updated_at
-- (Esto es una buena práctica en PostgreSQL y a menudo se maneja por defecto con ORMs como Eloquent de Laravel)

/*
CREATE OR REPLACE FUNCTION set_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_product_updated_at
BEFORE UPDATE ON products
FOR EACH ROW
EXECUTE PROCEDURE set_updated_at_column();
*/

CREATE TABLE inventory (
    product_id INTEGER PRIMARY KEY REFERENCES products(id) ON DELETE CASCADE,
    stock_quantity INTEGER NOT NULL DEFAULT 0 CHECK (stock_quantity >= 0),
    last_stock_update TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Opcional: Función y trigger para actualizar automáticamente la columna last_stock_update
/*
CREATE OR REPLACE FUNCTION set_inventory_update_time()
RETURNS TRIGGER AS $$
BEGIN
    NEW.last_stock_update = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_inventory_timestamp
BEFORE UPDATE ON inventory
FOR EACH ROW
EXECUTE PROCEDURE set_inventory_update_time();
*/

INSERT INTO products (name, description, price, sku) VALUES
('Laptop Ultraligera 13"', 'Potente laptop con procesador de última generación y 16GB RAM.', 1250.99, 'LT-UL-13-A'),
('Monitor Curvo 27" 4K', 'Monitor de alta resolución ideal para diseño gráfico y gaming.', 459.50, 'MN-CRV-27-B'),
('Teclado Mecánico RGB', 'Teclado con switches táctiles y retroiluminación RGB personalizable.', 89.90, 'TC-MEC-RGB-C');

INSERT INTO inventory (product_id, stock_quantity) VALUES
(
    (SELECT id FROM products WHERE sku = 'LT-UL-13-A'),
    15 -- 15 unidades de la Laptop Ultraligera
),
(
    (SELECT id FROM products WHERE sku = 'MN-CRV-27-B'),
    30 -- 30 unidades del Monitor Curvo
),
(
    (SELECT id FROM products WHERE sku = 'TC-MEC-RGB-C'),
    120 -- 120 unidades del Teclado Mecánico
);

