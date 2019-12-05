(ns aoc2019.day1
  (:gen-class))

(defn fuel
  ""
  [mass]
  (max 0 (- (quot mass 3) 2)))

(defn fuel2
  ""
  [mass]
  (if (= 0 (fuel (fuel mass)))
    (fuel mass)
    (+ (fuel mass) (fuel2 (fuel mass)))))

(defn part1
  ""
  [input]
  (reduce + (map fuel input)))

(defn part2
  ""
  [input]
  (reduce + (map fuel2 input)))

(defn -main
  [input]
  (do
    (println "part 1:" (part1 (map #(Integer/parseInt %) input)))
    (println "part 2:" (part2 (map #(Integer/parseInt %) input)))))
