# Orwell

### Scrabble Helper

SQL:

-- Populate it
```sql
UPDATE words SET characters = regexp_split_to_array(word, '') WHERE characters IS NULL;
```

-- Add points to words
```sql
UPDATE words SET points =
  (
      SELECT SUM(points)
      FROM ( SELECT regexp_split_to_table(words.word,E'(?=.)') the_word) tab 
      LEFT JOIN alphas ON the_word = letter
  )
WHERE points IS NULL
```

-- If needed
```sql
CREATE OR REPLACE FUNCTION sort_chars(text) RETURNS text AS
    $func$
SELECT array_to_string(ARRAY(SELECT unnest(string_to_array($1 COLLATE "C", NULL)) c ORDER BY c), '')
    $func$  LANGUAGE sql IMMUTABLE;
```