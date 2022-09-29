CREATE TABLE IF NOT EXISTS public.%s (
  id SERIAL PRIMARY KEY,
  author_id INT NOT NULL,
  category_id INT NOT NULL,
  created_ip VARCHAR(255) NOT NULL, 
  created_timestamp TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated_timestamp TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);