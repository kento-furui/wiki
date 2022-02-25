SELECT parentNameUsageID, COUNT(*) FROM taxa
JOIN eols ON taxa.EOLid = eols.EOLid
WHERE taxa.taxonRank = 'species'
AND eols.jp is not null 
GROUP BY parentNameUsageID

SELECT parentNameUsageID, sum(jp) AS s FROM numbers 
JOIN taxa ON taxa.taxonID = numbers.taxonID
GROUP BY parentNameUsageID
HAVING s > 0


UPDATE numbers,
(
SELECT parentNameUsageID, COUNT(*) AS c FROM taxa
JOIN eols ON taxa.EOLid = eols.EOLid
WHERE taxa.taxonRank = 'species'
AND eols.en is not null 
GROUP BY parentNameUsageID
) AS t
SET numbers.en = t.c 
WHERE numbers.taxonID = t.parentNameUsageID

UPDATE numbers,
(SELECT parentNameUsageID, sum(en) AS s FROM numbers 
JOIN taxa ON taxa.taxonID = numbers.taxonID
GROUP BY parentNameUsageID
HAVING s > 0) as t 
SET numbers.en = t.s
WHERE numbers.taxonID = t.parentNameUsageID

UPDATE numbers,
(SELECT parentNameUsageID, sum(img) AS s FROM numbers 
JOIN taxa ON taxa.taxonID = numbers.taxonID
GROUP BY parentNameUsageID
HAVING s > 0) as t 
SET numbers.img = t.s
WHERE numbers.taxonID = t.parentNameUsageID
