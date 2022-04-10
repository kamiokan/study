class Gear
  attr_reader :chain_ring, :cog, :wheel

  def initialize(chain_ring, cog, rim, tire)
    @chain_ring = chain_ring
    @cog = cog
    @wheel = Wheel.new(rim, tire)
  end

  def ratio
    chain_ring / cog.to_f
  end

  def gear_inches
    ratio * (wheel.diameter)
  end

  Wheel = Struct.new(:rim, :tire) do
    def diameter
      rim + (tire * 2)
    end
  end
end

puts Gear.new(52, 11, 26, 1.5).gear_inches
puts Gear.new(52, 11, 24, 1.25).gear_inches
