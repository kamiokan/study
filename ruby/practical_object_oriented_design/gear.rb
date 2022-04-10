class Gear
  attr_reader :chain_ring, :cog

  def initialize(chain_ring, cog)
    @chain_ring = chain_ring
    @cog = cog
  end

  def ratio
    chain_ring / cog.to_f
  end
end

puts Gear.new(52, 11).ratio
puts Gear.new(30, 27).ratio
