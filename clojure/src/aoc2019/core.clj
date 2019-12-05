(ns aoc2019.core
  (:gen-class))

(defn input [day]
  (clojure.string/split (slurp (str "../input/" day ".txt")) #"\n"))

(defn -main
  [& args]
  (if (= 1 (count args))
    (do
      (def day (first args))
      (def command (str "aoc2019.day" day))
      (require (symbol command))
      (apply (ns-resolve (symbol command) '-main) [(input day)]))
    (println "usage: aoc2019 <day>")))
