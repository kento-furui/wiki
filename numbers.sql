// numbersテーブル削除
TRUNCATE numbers;

// species初期データ投入
INSERT INTO numbers (taxonID, species)
SELECT taxa.parentNameUsageID, COUNT(taxa.parentNameUsageID) FROM taxa
WHERE taxa.parentNameUsageID <> "" 
AND taxa.taxonRank = "species"
GROUP BY taxa.parentNameUsageID;

// genus
INSERT INTO numbers (taxonID, genus)
SELECT * FROM (
    SELECT taxa.parentNameUsageID, COUNT(taxa.parentNameUsageID) AS c FROM taxa
    WHERE taxa.parentNameUsageID <> "" AND taxa.taxonRank = "genus"
    GROUP BY taxa.parentNameUsageID
) AS s
ON DUPLICATE KEY UPDATE genus = s.c 

INSERT INTO numbers (taxonID, family)
SELECT * FROM (
    SELECT taxa.parentNameUsageID, COUNT(taxa.parentNameUsageID) AS c FROM taxa
    WHERE taxa.parentNameUsageID <> "" AND taxa.taxonRank = "family"
    GROUP BY taxa.parentNameUsageID
) AS s
ON DUPLICATE KEY UPDATE family = s.c 

INSERT INTO numbers (taxonID, `order`)
SELECT * FROM (
    SELECT taxa.parentNameUsageID, COUNT(taxa.parentNameUsageID) AS c FROM taxa
    WHERE taxa.parentNameUsageID <> "" AND taxa.taxonRank = "order"
    GROUP BY taxa.parentNameUsageID
) AS s
ON DUPLICATE KEY UPDATE `order` = s.c 

INSERT INTO numbers (taxonID, `class`)
SELECT * FROM (
    SELECT taxa.parentNameUsageID, COUNT(taxa.parentNameUsageID) AS c FROM taxa
    WHERE taxa.parentNameUsageID <> "" AND taxa.taxonRank = "class"
    GROUP BY taxa.parentNameUsageID
) AS s
ON DUPLICATE KEY UPDATE `class` = s.c 

INSERT INTO numbers (taxonID, `phylum`)
SELECT * FROM (
    SELECT taxa.parentNameUsageID, COUNT(taxa.parentNameUsageID) AS c FROM taxa
    WHERE taxa.parentNameUsageID <> "" AND taxa.taxonRank = "phylum"
    GROUP BY taxa.parentNameUsageID
) AS s
ON DUPLICATE KEY UPDATE `phylum` = s.c 


INSERT INTO numbers (taxonID, `kingdom`)
SELECT * FROM (
    SELECT taxa.parentNameUsageID, COUNT(taxa.parentNameUsageID) AS c FROM taxa
    WHERE taxa.parentNameUsageID <> "" AND taxa.taxonRank = "kingdom"
    GROUP BY taxa.parentNameUsageID
) AS s
ON DUPLICATE KEY UPDATE `kingdom` = s.c 





// species合算データ,PK重複するのでこのままINSERTできない
REPLACE INTO numbers (taxonID, species)
SELECT parentNameUsageID, SUM(numbers.species) FROM `taxa` 
RIGHT JOIN numbers ON taxa.taxonID = numbers.taxonID
WHERE parentNameUsageID <> ''
GROUP BY taxa.parentNameUsageID

// species総数確認
SELECT COUNT(taxonID) FROM `taxa` WHERE taxonRank = 'species' AND taxonomicStatus IN ('valid', 'accepted')
// 1,960,155


SELECT SUM(species), SUM(genus), SUM(family), SUM(`order`), SUM(class), SUM(phylum), SUM(kingdom) FROM `numbers` LEFT JOIN taxa ON taxa.taxonID = numbers.taxonID
WHERE taxa.parentNameUsageID = "EOL-000002323132"
GROUP BY taxa.parentNameUsageID