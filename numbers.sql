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

SELECT concat("http://wiki.mydns.jp/taxon/",taxa.parentNameUsageID) url, numbers.img, COUNT(eols.img) FROM `eols` 
JOIN taxa ON eols.EOLid = taxa.EOLid
JOIN numbers ON taxa.parentNameUsageID = numbers.taxonID
WHERE taxa.taxonRank = "species"
AND eols.img IS NOT NULL 
GROUP BY taxa.parentNameUsageID
HAVING numbers.img <> COUNT(eols.img)
ORDER BY COUNT(eols.img) DESC

SELECT taxa.parentNameUsageID, numbers.iucn, COUNT(eols.iucn) FROM `eols` 
JOIN taxa ON eols.EOLid = taxa.EOLid
JOIN numbers ON taxa.parentNameUsageID = numbers.taxonID
WHERE taxa.taxonRank = "species"
AND eols.iucn IS NOT NULL 
GROUP BY taxa.parentNameUsageID
HAVING numbers.iucn <> COUNT(eols.iucn)
ORDER BY COUNT(eols.iucn) DESC

SELECT parentNameUsageID FROM taxa WHERE taxonID IN
(
SELECT taxa.parentNameUsageID FROM `eols` 
JOIN taxa ON eols.EOLid = taxa.EOLid
JOIN numbers ON taxa.parentNameUsageID = numbers.taxonID
WHERE taxa.taxonRank = "species"
AND eols.img IS NOT NULL 
GROUP BY taxa.parentNameUsageID
HAVING numbers.img <> COUNT(eols.img)
)

UPDATE numbers,
(
SELECT parentNameUsageID, COUNT(iucns.status) AS c FROM taxa
JOIN iucns ON taxa.taxonID = iucns.taxonID
WHERE taxa.taxonRank = 'species'
AND iucns.status IS NOT NULL
GROUP BY parentNameUsageID
) AS t
SET numbers.en = t.c
WHERE numbers.taxonID = t.parentNameUsageID


UPDATE numbers,
(SELECT parentNameUsageID, sum(iucn) AS s FROM numbers 
JOIN taxa ON taxa.taxonID = numbers.taxonID
GROUP BY parentNameUsageID
ORDER BY parentNameUsageID DESC
HAVING s > 0
) as t 
SET numbers.iucn = t.s
WHERE numbers.taxonID = t.parentNameUsageID
