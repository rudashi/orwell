# Orwell

### Scrabble Helper

SQL:

-- Populate it
```sql
UPDATE words SET characters = regexp_split_to_array(word, '');
```

-- A GIN index on the characters column
```sql
CREATE INDEX ix_word_chars ON words USING GIN (characters);
```

-- An index on word length
```sql
CREATE INDEX ix_word_length ON words (char_length(word));
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
