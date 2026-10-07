-- OPTIONAL sample data (uses the image/mp3 files already in admin/uploads).
-- Titles, moods and prices are placeholders - edit them from the admin panel later.
-- Skip this file if you import your own XAMPP database export.

INSERT INTO products (title, description, mood, category, image, price) VALUES
('Mood Mug', 'Mood Mug to match your mood.', 'happy', 'Self-Care', '1758321822_mugs.jpg', 12.99),
('Comfort Cushion', 'Comfort Cushion to match your mood.', 'sad', 'Relaxation', '1758321874_cushion.jpg', 19.99),
('Mini Bluetooth Speaker', 'Mini Bluetooth Speaker to match your mood.', 'excited', 'Energy', '1758321943_mini-speaker.jpg', 29.99),
('Gratitude Notebook', 'Gratitude Notebook to match your mood.', 'anxious', 'Mindfulness', '1758322034_notebook.jpg', 9.99),
('Neon Mood Light', 'Neon Mood Light to match your mood.', 'excited', 'Decor', '1758322209_neon-light.jpg', 24.99),
('Candy Jar', 'Candy Jar to match your mood.', 'happy', 'Self-Care', '1758323415_candy-jar.jpg', 14.99),
('Good Vibes T-Shirt', 'Good Vibes T-Shirt to match your mood.', 'happy', 'Energy', '1758323500_t-shirt.jpg', 18.99),
('Hydration Bottle', 'Hydration Bottle to match your mood.', 'calm', 'Self-Care', '1758324726_water-bottle.jpg', 15.99),
('Indoor Plant', 'Indoor Plant to match your mood.', 'calm', 'Decor', '1758324780_indoor-plant.jpg', 22.99),
('Stress Relief Keychain', 'Stress Relief Keychain to match your mood.', 'angry', 'Relaxation', '1758324864_keychains.jpg', 6.99);

INSERT INTO songs (title, singer, mood, file_path) VALUES
('Kahani Meri', 'Kaifi Khalil', 'sad', '1756669078_Kahani Meri - Kaifi Khalil (pagalall.com).mp3'),
('Soch Na Sake', 'Arijit Singh', 'sad', '1758357317_Soch Na Sake Airlift 128 Kbps.mp3'),
('Meri Aashiqui', 'Aashiqui 2', 'calm', '1758357350_Meri Aashiqui Aashiqui 2 128 Kbps (1).mp3'),
('Tu Jhoom', 'Abida Parveen', 'excited', '1758357382_Tu Jhoom Abida Parveen 128 Kbps.mp3'),
('Jo Tum Mere Ho', 'Anuv Jain', 'calm', '1758357427_Jo Tum Mere Ho Anuv Jain 128 Kbps.mp3'),
('Lambiya Judaiyan', 'Bilal Saeed', 'sad', '1758366178_Lambiya Judaiyan Bilal Saeed 128 Kbps.mp3'),
('Tum Se Hi', 'Jab We Met', 'happy', '1758366271_Tum Se Hi Jab We Met 128 Kbps (1).mp3'),
('Karde Karam', 'Harish Verma', 'anxious', '1758366332_Karde Karam Harish Verma 128 Kbps.mp3'),
('Malang Sajna', 'Sachet Tandon', 'happy', '1758366406_Malang Sajna Sachet Tandon 128 Kbps.mp3'),
('O Maahi', 'Dunki', 'angry', '1758366466_O Maahi Dunki 128 Kbps (1).mp3');

INSERT INTO quotes (text, author, mood) VALUES
('Happiness is not by chance, but by choice.', 'Jim Rohn', 'happy'),
('Tough times never last, but tough people do.', 'Robert H. Schuller', 'sad'),
('For every minute you are angry you lose sixty seconds of happiness.', 'Ralph Waldo Emerson', 'angry'),
('You don''t have to control your thoughts. You just have to stop letting them control you.', 'Dan Millman', 'anxious'),
('Energy and persistence conquer all things.', 'Benjamin Franklin', 'excited'),
('Calm mind brings inner strength and self-confidence.', 'Dalai Lama', 'calm');
