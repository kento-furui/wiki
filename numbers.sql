TRUNCATE numbers;

INSERT INTO numbers (taxonID, species)
SELECT taxa.parentNameUsageID, COUNT(taxa.parentNameUsageID) FROM taxa
WHERE taxa.parentNameUsageID <> "" 
AND taxa.taxonRank = "species"
GROUP BY taxa.parentNameUsageID