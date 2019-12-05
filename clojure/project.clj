(defproject aoc2019 "0.1.0-SNAPSHOT"
  :description "Advent of Code 2019"
  :url "https://github.com/lewinski/advent-of-code-2019"
  :dependencies [[org.clojure/clojure "1.10.0"]]
  :plugins [[lein-cljfmt "0.6.6"]]
  :main ^:skip-aot aoc2019.core
  :target-path "target/%s"
  :profiles {:uberjar {:aot :all}})
